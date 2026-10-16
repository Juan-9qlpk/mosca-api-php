<?php
class Database {

    public function connect(){
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        try{
            return new mysqli(
                getenv('DB_HOST'),
                getenv('DB_USERNAME'),
                getenv('DB_PASSWORD'),
                getenv('DB_DATABASE'),
                getenv('DB_PORT')
            );

        } catch (mysqli_sql_exception $e){
            json_encode([
                'error' => "Error de Conexión",
                'codigo' => $e->getCode(),
                'ok' => false,
            ]);
            throw $e;
        }
    }
}
