<?php

class Logger
{
    public static function LogOperacion($request, $handler)
    {
        $requestType = $request->getMethod();
        $response = $handler->handle($request);
        $response->getBody()->write(
        PHP_EOL.
        'middleware function LogOperacion requestType: '
        .$requestType
        );
        // $retorno = $next($request, $response);
        return $response;
    }

    public static function VerificarCredencial($request, $handler)
    {
        $requestType = $request->getMethod();
        
        // $handler funcion que tiene slim para direccion a que lado voy a ir
        // freno la ejecucion del middelware y me fui a lo que sigue,Ej: usuario controller
        // $response obtengo todo lo que hizo el controller y sigo
        $response = $handler->handle($request); 
        
        // Vuelvo del verificador de credenciales.
        switch($requestType)
        {
            case 'GET':
                $response->getBody()->write(PHP_EOL.
                'NO necesita credenciales para GET.'.PHP_EOL.
                'API=>'.$requestType.PHP_EOL.
                'Vuelvo del verificador de credenciales.'
                .PHP_EOL);
            break;
            case 'POST':
                $data = $request->getParsedBody();
                $nombre = $data['nombre'];
                $perfil = $data['perfil'];
                if($perfil == 'admin'){
                    $response->getBody()->write(PHP_EOL.
                    'Verifico Credenciales: '.$requestType
                    .PHP_EOL
                    .PHP_EOL.
                    '<b>Bienvenido '.$nombre.'</b>'
                    .PHP_EOL.
                    'API=>'.$requestType
                    .PHP_EOL.
                    'Vuelvo del verificador de credenciales.'
                    .PHP_EOL);
                }else{
                    $response->getBody()->write(PHP_EOL.
                        'Verifico Credenciales: '.$requestType
                        .PHP_EOL
                        .PHP_EOL.
                        '<b>NO tiene habilitado ingreso.</b>'
                        .PHP_EOL.
                        'API=>'.$requestType
                        .PHP_EOL.
                        'Vuelvo del verificador de credenciales.'
                        .PHP_EOL);
                }
            break;
        }
        $response->getBody()->write('write en body. requestType: '.$requestType);
        // $retorno = $next($request, $response);
        return $response;
    }

    public static function VerificarJson($request, $handler)
    {
        $requestType = $request->getMethod();
        $response = $handler->handle($request);
        
        
        // Vuelvo del verificador de credenciales.
        switch($requestType)
        {
            case 'GET':
                $response->getBody()->write(
                'NO necesita Json para GET.'.PHP_EOL.
                'API=>'.$requestType.PHP_EOL.
                'Vuelvo del verificador de credenciales.');
            break;
            case 'POST':
                $data = $request->getParsedBody();
                $nombre = $data['nombre'];
                $perfil = $data['perfil'];
                $payload = json_encode($data);
                if($perfil == 'admin'){
                    $response->getBody()->write(
                    'Verifico Json: '.$requestType.PHP_EOL.PHP_EOL.
                    '<b>Bienvenido '.$nombre.'</b>'.PHP_EOL.PHP_EOL.
                    'API=>'.$requestType.PHP_EOL.PHP_EOL.
                    'Vuelvo del verificador de Json.');
                    $response->withStatus(200)->getBody()->write(PHP_EOL.$payload);
                }else{
                    $response->getBody()->write(
                        'Verifico Json: '.$requestType.PHP_EOL.PHP_EOL.
                        '<b>NO tiene habilitado ingreso.</b>'.PHP_EOL.
                        'API=>'.$requestType.PHP_EOL.PHP_EOL.
                        'Vuelvo del verificador de Json.');
                        $response->withStatus(401);
                }
            break;
        }
        $response->getBody()->write(PHP_EOL.'/app/usuarios requestType: '.$requestType);
        // $retorno = $next($request, $response);
        return $response;
    }
}