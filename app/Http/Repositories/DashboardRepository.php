<?php

namespace App\Http\Repositories;

use App\Models\Question;
use App\Models\User;
use App\Models\Subject;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use Imagick;

class DashboardRepository
{
    protected $mUser;
    protected $mSubject;
    protected $mQuestion;
    protected $mUserTest;
    protected $carbon;

    public const MONTH = [
        1 => "ENERO",
        2 => "FEBRERO",
        3 => "MARZO",
        4 => "ABRIL",
        5 => "MAYO",
        6 => "JUNIO",
        7 => "JULIO",
        8 => "AGOSTO",
        9 => "SEPTIEMBRE",
        10 => "OCTUBRE",
        11 => "NOVIEMBRE",
        12 => "DICIEMBRE"
    ];

    public function __construct()
    {
        $this->mUser = new User();
        $this->mSubject = new Subject();
        $this->mQuestion = new Question();
        $this->carbon = new Carbon();
        $this->mUserTest = new UserTest();
    }

    public function getAllUsers()
    {
        return $this->mUser->select('*')
            ->where('type_id', User::TYPES['student'])
            ->get();
    }

    public function getStats()
    {
        $countUsers = $this->mUser
            ->where('type_id', User::TYPES['student'])
            ->whereNull('deleted_at')
            ->count();

        $countAdmins = $this->mUser
            ->where('type_id', User::TYPES['admin'])
            ->count();

        $countSubjects = $this->mSubject
            ->whereNull('deleted_at')
            ->count();

        $countQuestions = $this->mQuestion
            ->whereNull('deleted_at')
            ->count();
        // $now = Carbon::now();
        // $last = Carbon::now()->subMinutes(10);
        // $command = "sudo aws cloudwatch get-metric-statistics --namespace AWS/EC2 --metric-name CPUUtilization  --period 3600 --statistics Maximum --dimensions Name=InstanceId,Value=i-0b5983b042c435098 --start-time " . $last->toISOString() . " --end-time " . $now->toISOString();
        // $responseCommand = shell_exec($command);
        // $cpuUsage = json_decode($responseCommand);
        // if (count($cpuUsage->Datapoints) > 0) {
        //     $keyLast = array_key_last($cpuUsage->Datapoints);
        // }

        return [
            'countUsers' => $countUsers,
            'countAdmins' => $countAdmins,
            'countSubjects' => $countSubjects,
            'countQuestions' => $countQuestions,
            // 'cpuUsage' => count($cpuUsage->Datapoints) > 0 ? round($cpuUsage->Datapoints[$keyLast]->Maximum, 2) . '%' : '0.1%'
            'cpuUsage' => '0.1%'
        ];
    }

    public function getBarChart()
    {
        $questions = DB::select('CALL subjects_count_test_global');
        $subjectName = [];
        $reps = [];
        foreach ($questions as $subject) {
            $subjectName[] = $subject->name;
            $reps[] = $subject->repeticion;
        }

        return [
            'labels' => $subjectName,
            'info' => $reps
        ];
    }

    public function getUserProgress($id)
    {
        return $this->mUser->select('subjects.name', 'user_tests.*')
            ->join('user_tests', 'users.id', '=', 'user_tests.user_id')
            ->join('subjects', 'subjects.id', '=', 'user_tests.subject_id')
            ->where('users.id', $id)
            ->where('users.type_id', User::TYPES['student'])
            ->whereNull('users.deleted_at')
            ->get();
    }

    public function getBalance($request)
    {
        $dateInit = $this->carbon->parse($request->date)->format('Y-01-01');
        $dateEnd = $this->carbon->parse($request->date)->format('Y-12-31');
        $balance = DB::select('CALL balance_per_year("' . $dateInit . '","' . $dateEnd . '")');
        $months = [];
        $amounts = [];

        foreach ($balance as $bal) {
            $months[] = self::MONTH[$bal->meses];
            $amounts[] = $bal->monto;
        }

        return [
            'labels' => $months,
            'info' => $amounts
        ];
    }

    public function test()
    {
        $file = 'questions.xlsx';
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
        $questions = Question::select('id', 'image')->whereNotNull('image')->get()->keyBy('id');
        $sheet = $spreadsheet->getActiveSheet();
        $i = 0;
        $arr = $sheet->rangeToArray('A2:' . $sheet->getHighestColumn() . $sheet->getHighestDataRow(), null, true, true, true);


        $sheet = $spreadsheet->getSheet(0);


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
                if (array_key_exists($j, $columnMap)) {
                    $withKeys[$k][$columnMap[$j]] = $data;
                }
            }
        }

        $subjects = [];
        foreach ($withKeys as $question) {
            $subjects[] = $question['subject_id'];
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
                    'points' => $question['points'],
                    'question' => $question['question'],
                    'answer' => $question['answer'],
                    'a' => $question['a'],
                    'b' => $question['b'],
                    'c' => $question['c'],
                    'explanation' => $question['explanation'],
                    'image' => $question['image'] ?? null
                ]
            );
        }
    }

    public function compressAndUploadImage($imageData, $drawing)
    {
        $md5 = md5($imageData);
        $imageName = $drawing->getName();
        $imageExt = $drawing->getExtension();

        $imagick = new Imagick();
        $imagick->readImageBlob($imageData);

        // Resize and compress the image
        // $imagick->resizeImage(800, 600, Imagick::FILTER_LANCZOS, 1);
        $imagick->setImageCompressionQuality(75); // Adjust quality
        $imagick->setImageFormat($imageExt);
        $tempPath = sys_get_temp_dir() . '/' . $imageName . '.' . $imageExt;
        $imagick->writeImage($tempPath);
        $imageData = file_get_contents($tempPath);
        Storage::disk('s3')->put($md5 . '.' . $imageExt, file_get_contents($tempPath));
        $imagick->clear();
    }
}
