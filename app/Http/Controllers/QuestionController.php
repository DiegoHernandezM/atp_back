<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Models\Question;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{

    public function index()
    {
        $questions = Question::with('subject')->get();
        return response()->json([
            'data' => $questions,
        ], 200);
    }

    public function preload()
    {
        $questions = Question::get();
        return response()->json([
            'data' => $questions,
        ], 200);
    }


    // show
    public function show($id)
    {
        $question = Question::find($id);
        return response()->json([
            'data' => $question,
        ], 200);
    }

    public function create(StoreQuestionRequest $request)
    {
        $question = new Question();
        $question->subject_id = $request->subject_id;
        $question->points = $request->points;
        $question->question = $request->question;
        $question->answer = $request->answer;
        $question->a = $request->a;
        $question->b = $request->b;
        $question->c = $request->c;
        $question->d = $request->d;
        $question->e = $request->e;
        $question->explanation = $request->explanation;
        $question->save();
        return response()->json([
            'data' => $question,
        ], 201);
    }

    // update
    public function update(UpdateQuestionRequest $request, $id)
    {
        $question = Question::find($id);
        $question->subject_id = $request->subject_id;
        $question->points = $request->points;
        $question->question = $request->question;
        $question->answer = $request->answer;
        $question->a = $request->a;
        $question->b = $request->b;
        $question->c = $request->c;
        $question->d = $request->d;
        $question->e = $request->e;
        $question->explanation = $request->explanation;
        $question->save();
        return response()->json([
            'data' => $question,
        ], 200);
    }
    // delete
    public function delete($id)
    {
        $question = Question::find($id);
        $question->delete();
        return response()->json([
            'data' => $question,
        ], 200);
    }

    // load questions from excel
    public function massLoadQuestions(Request $request)
    {
        // validate request
        $request->validate([
            'excel' => 'required|file|mimes:xlsx,xls',
        ]);

        // get file
        $file = $request->file('excel');
        $questions = Question::whereNotNull('image')->get()->keyBy('id');
        // check if file is excel
        if ($file->extension() === 'xlsx' || $file->extension() === 'xls') {
            $columnNames = [
                'id' => 'id',
                'materia/categoria' => 'subject_id',
                'puntos' => 'points',
                'pregunta' => 'question',
                'respuesta' => 'answer',
                'answer a' => 'a',
                'answer b' => 'b',
                'answer c' => 'c',
                'answer d' => 'd',
                'answer e' => 'e',
                'justificacion' => 'explanation',
            ];
            // load excel file
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $columnMap = [];
            for ($i = 1; $i < 4; $i++) { // BUSCAMOS NOMBRES DE COLUMNAS EN FILAS 1 - 3 DE LA `A` A LA `M` Y MAPEAMOS
                foreach (range('A', 'Q') as $columnID) {
                    $col = strtolower(trim(preg_replace('/\s+/', ' ', $sheet->getCell($columnID . $i)->getValue())));
                    if (array_key_exists($col, $columnNames)) {
                        $columnMap[$columnID] = $columnNames[$col];
                    }
                }
            }

            $dif = array_diff(array_values($columnNames), array_values($columnMap));
            $missingColumns = [];
            foreach ($dif as $key => $value) {
                $missingColumns[] = $value;
            }
            if (count($missingColumns) > 0) {
                return response()->json([
                    'message' => 'Missing columns: ' . implode(', ', $missingColumns),
                ], 400);
            }

            $arr = $sheet->rangeToArray(min(array_keys($columnMap)) . '2:' . max(array_keys($columnMap)) . $sheet->getHighestDataRow(), null, true, true, true);

            $withKeys = [];
            foreach ($arr as $k => $line) {
                foreach ($line as $j => $data) {
                    if (array_key_exists($j, $columnMap) && $data !== null) {
                        $withKeys[$k][$columnMap[$j]] = $data;
                    }
                }
            }

            $drawings = $sheet->getDrawingCollection();

            $subjects = [];
            foreach ($withKeys as $question) {
                $subjects[] = $question['subject_id'];
            }

            $subjects = array_unique($subjects);
            $subjectMap = [];
            foreach ($subjects as $key => $sub) {
                $subject = Subject::where('name', 'like', "%" . $sub . "%")->first();
                if (!$subject) {
                    $subject = new Subject();
                    $subject->name = $sub;
                    $subject->save();
                }
                $subjectMap[$sub] = $subject->id;
            }

            foreach ($drawings as $drawing) {
                if ($drawing instanceof \PhpOffice\PhpSpreadsheet\Worksheet\Drawing) {
                    $k = (int)filter_var($drawing->getCoordinates(), FILTER_SANITIZE_NUMBER_INT);

                    $path = $drawing->getPath();

                    $imageData = file_get_contents($path);
                    $md5 = md5($imageData);
                    if (!isset($questions[$withKeys[$k]['id']])) {
                        $this->compressAndUploadImage($imageData, $drawing);
                    }
                    if (isset($questions[$withKeys[$k]['id']])) {
                        if ($questions[$withKeys[$k]['id']]->image != $md5 . '.' . $drawing->getExtension()) {
                            $this->compressAndUploadImage($imageData, $drawing);
                            Storage::disk('s3')->delete($questions[$withKeys[$k]['id']]->image);
                        }
                    }
                    $withKeys[$k]['image'] = $md5 . '.' . $drawing->getExtension();
                }
            }

            foreach ($withKeys as $key => $question) {
                if (isset($questions[$question['id']])) {
                    if ($questions[$question['id']]->image && !isset($question['image'])) {
                        Storage::disk('s3')->delete($questions[$question['id']]->image);
                    }
                }

                DB::table('questions')->updateOrInsert(
                    [
                        'id' => $question['id'],
                    ],
                    [
                        'subject_id' => $subjectMap[$question['subject_id']],
                        'points' => trim($question['points']) ?? 1,
                        'question' => $question['question'],
                        'answer' => trim($question['answer']),
                        'a' => $question['a'],
                        'b' => $question['b'],
                        'c' => $question['c'],
                        'd' => $question['d'] ?? null,
                        'e' => $question['e'] ?? null,
                        'explanation' => $question['explanation'] ?? null,
                        'image' => $question['image'] ?? null
                    ]
                );
            }



            // return response
            return response()->json([
                'data' => $withKeys,
            ], 200);
        } else {
            // return response
            return response()->json([
                'message' => 'Invalid file type',
            ], 400);
        }
    }

    public function compressAndUploadImage($imageData, $drawing)
    {
        $md5 = md5($imageData);
        $imageName = $drawing->getName();
        $imageExt = $drawing->getExtension();

        $imagick = new \Imagick();
        $imagick->readImageBlob($imageData);

        // Resize and compress the image
        $imagick->setImageCompressionQuality(75); // Adjust quality
        $imagick->setImageFormat($imageExt);
        $tempPath = sys_get_temp_dir() . '/' . $imageName . '.' . $imageExt;
        $imagick->writeImage($tempPath);
        $imageData = file_get_contents($tempPath);
        Storage::disk('s3')->put($md5 . '.' . $imageExt, file_get_contents($tempPath));
        $imagick->clear();
    }

    public function getBySubject($subject)
    {
        $questions = Question::where('subject_id', $subject)->get();
        return response()->json([
            'data' => $questions,
        ], 200);
    }

    public function getRandom()
    {
        $questions = Question::limit(310)->get();
        return response()->json([
            'data' => $questions,
        ], 200);
    }
}
