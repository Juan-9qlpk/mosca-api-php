<?php

class Tablero{
    public $tablero;
    public $tamano;
    public $intentos;
    public $id = 0;
    public $intentos_iniciales;
    public $posicion_mosca;
    public $estado;


    public function __construct($tamano = 10, $intentos = 5){
        $this->tamano = $tamano;
        $this->intentos = $intentos;
        $this->tablero = array_fill(0,$this->tamano, 0);
        $this->estado = "activo";
        $this->colocarMosca();
    }

    public function rendirse(){
        $this->estado = "rendido";
    }

    public function devolverEstado(){
        return $this->estado;
    }

    public function colocarMosca(){
        $pos = rand(0, ($this->tamano-1));
        $this->tablero[$pos] = 1;
        $this->posicion_mosca = $pos;
    }

    public function devolverTamano(){
        return $this->tamano;
    }
    public function ponerTodoACero(){
        $this->tablero = array_fill(0,$this->tamano, 0);
    }

    public function posicionMosca(){
        $posicion = 0;
        for ($i = 0; $i < $this->tamano; $i++){
            if ($this->tablero[$i] == 1){
                    $posicion = $i;
                    $this->posicion_mosca = $i;
            }
        }
        return $posicion;
    }

    public function devolverIntentos(){
        return $this->intentos;
    }

    public function mostrarTablero(){
        $imprimirTablero = "";
        for ($i = 0; $i < $this->tamano; $i++){
            $imprimirTablero = $imprimirTablero . $this->tablero[$i] . "";
        }
        return $imprimirTablero;
    }

    public function golpe($pos)
    {
        if ($pos < $this->tamano && $pos >= 0)
        {
            if ($this->intentos > 0)
            {
                $totalIntentos =  $this->intentos - 1;
                $this->intentos = $totalIntentos;

                if ($this->tablero[$pos] ==  1) 
                {
                    return 1;
                } 
                else if (($pos > 0 && $this->tablero[$pos - 1] == 1) || ($pos < $this->tamano - 1 && $this->tablero[$pos + 1] == 1)) 
                {
                    self::ponerTodoACero();
                    self::colocarMosca();
                    return 2;
                } 
                else 
                {
                    if ($this->intentos == 0)
                    {
                        Auxiliar::perder();
                        return -1;
                    }
                    return 3;
                }   
            } 
        } 
    }
    
}