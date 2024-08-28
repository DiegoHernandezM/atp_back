<?php

namespace App\Http\Controllers;

class ApiResponses
{
    /**
     * @SWG\Definition(
     *  definition="OkResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="Ok."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function ok($message = 'Ok.')
    {
        return response()->json([
            'message' => $message,
        ], 200);
    }

    /**
     * @SWG\Definition(
     *  definition="OkObjectResponse",
     *  type="object"
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function okObject($object)
    {
        return response()->json($object, 200);
    }

    /**
     * @SWG\Definition(
     *  definition="CreatedResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="Recurso creado."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function created($message = 'Recurso creado.')
    {
        return response()->json([
            'message' => $message,
        ], 201);
    }

    /**
     * @SWG\Definition(
     *  definition="CreatedResponseWithObject",
     *  type="object",
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function createdWithObject($object)
    {
        return response()->json($object, 201);
    }

    /**
     * @SWG\Definition(
     *  definition="ResetContentResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="Contenido restablecido."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function resetContent($message = 'Contenido restablecido.')
    {
        return response()->json([
            'message' => $message,
        ], 205);
    }

    /**
     * @SWG\Definition(
     *  definition="AlreadyReportedResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="No se realizaron cambios en el recurso."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function alreadyReported($message = 'No se realizaron cambios en el recurso.')
    {
        return response()->json([
            'message' => $message,
        ], 208);
    }

    /**
     * @SWG\Definition(
     *     definition="AlreadyReportedResponseWithObject",
     *     type="object"
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function alreadyReportedWithObject($object)
    {
        return response()->json($object, 208);
    }

    /**
     * @SWG\Definition(
     *  definition="FoundResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="El recurso ya existe."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function found($message = 'El recurso ya existe.')
    {
        return response()->json([
            'message' => $message,
        ], 302);
    }

    /**
     * @SWG\Definition(
     *  definition="FoundResponseWithObject",
     *  type="object"
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function foundWithObject($object)
    {
        return response()->json($object, 302);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public static function streamFile($stream, $fileName)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With'
        ];

        return response()->streamDownload($stream, $fileName, $headers);
    }

    /**
     * @SWG\Definition(
     *  definition="BadRequestResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="Campos Inválidos."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function badRequest(string $message = 'Campos Inválidos.')
    {
        return response()->json([
            'message' => $message,
        ], 400);
    }

    /**
     * @SWG\Definition(
     *  definition="BadRequestValidationsResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="Campos Inválidos."
     *  ),
     *  @SWG\Property(
     *      property="errors",
     *      type="object",
     *      @SWG\Property(
     *          property="field_error",
     *          type="array",
     *          @SWG\Items(
     *              type="string"
     *          )
     *      ),
     *      required={"field_error"}
     *  ),
     *  required={"message", "errors"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function badRequestValidations($errors, string $message = 'Campos Inválidos.')
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], 400);
    }

    /**
     * @SWG\Definition(
     *  definition="UnauthorizedResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="No tiene permisos para ver el recurso solicitado."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function unauthorized(string $message = 'No tiene permisos para ver el recurso solicitado.')
    {
        return response()->json([
            'message' => $message,
        ], 401);
    }

    /**
     * @SWG\Definition(
     *  definition="NotFoundResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="El recurso solicitado no existe."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function notFound(string $message = 'El recurso solicitado no existe.')
    {
        return response()->json([
            'message' => $message,
        ], 404);
    }

    /**
     * @SWG\Definition(
     *  definition="UnsupportedMediaType",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="Campos Inválidos."
     *  ),
     *  @SWG\Property(
     *      property="errors",
     *      type="object",
     *      @SWG\Property(
     *          property="field_error",
     *          type="array",
     *          @SWG\Items(
     *              type="string"
     *          )
     *      ),
     *      required={"field_error"}
     *  ),
     *  required={"message", "errors"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function unsupportedMediaType($errors, string $message = 'Formato o codificación no soportado.')
    {
        return response()->json([
            'message' => $message,
            'errors' => $errors,
        ], 415);
    }

    /**
     * @SWG\Definition(
     *  definition="InternalServerErrorResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="El servicio no esta disponible en este momento."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function internalServerError(string $message = 'Error interno del servidor.')
    {
        return response()->json([
            'message' => $message,
        ], 500);
    }

    /**
     * @SWG\Definition(
     *  definition="ServiceUnavailableResponse",
     *  type="object",
     *  @SWG\Property(
     *      property="message",
     *      type="string",
     *      example="El servicio no esta disponible en este momento, intente mas tarde."
     *  ),
     *  required={"message"}
     * )
     */
    /**
     * @return \Illuminate\Http\Response
     */
    public static function serviceUnavailable(string $message = 'Servicio no disponible.')
    {
        return response()->json([
            'message' => $message,
        ], 503);
    }
}
