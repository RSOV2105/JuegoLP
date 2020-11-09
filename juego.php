<?php

    class Casilla {
        public $valor;
        public function __construct($val){
            $this->valor = $val;
        }
        public $casillaDerecha = NULL;
        public $casillaInferior = NULL;

    }
    class Tablero {
        private $tam = 0;
        public $primero = NULL;
        
        function __construct($tama){
            $this->tam = $tama;
        }
        private $camino = array();
        private $caminos = array();
        public function obtenerValorMatriz($pos1, $pos2){
            $pCasillaFila = $this->primero;
            for($i = 0; $i < $pos2; $i++){
                $pCasillaFila = $pCasillaFila->casillaInferior;
            }
            for($i = 0; $i < $pos1; $i++){
                $pCasillaFila = $pCasillaFila->casillaDerecha;
            }
            return $pCasillaFila->valor;
        }
        public function caminoCorto(){
            $temp = 0;
            $temp2 = 0;
            $temp3 = $this->tam - 1;
            $pesos = array();
            for($i = 0; $i < $this->tam; $i++){
                for($j = 0; $j < $this->tam; $j++){
                    $valor = ($i!=$this->tam-1)and($j!=$this->tam-1);
                    if($valor){
                        
                        if($i==0 and $j==0){
                            array_push($this->camino, "({$temp3},{$temp3})");
                            $temp = $this->relajacion1($i,$j,$this->tam - 1, $this->tam - 1, 0);
                            $temp2 = $temp;
                            array_push($this->caminos, $this->camino);
                            array_push($pesos, $temp);
                            $this->camino = array();
                            
                        }
                        
                        else{
                            array_push($this->camino, "({$temp3},{$temp3})");
                            $tem1 =  $this->relajacion1($i,$j,$this->tam - 1, $this->tam - 1, 0) + $this->relajacion1(0, 0, $i, $j, 0) - $this->obtenerValorMatriz($i,$j);
                            if($tem1<=$temp2){
                                array_push($this->caminos, $this->camino);
                                array_push($pesos, $tem1);
                                $temp = $tem1;
                            }
                            
                            $this->camino = array();

                            array_push($this->camino, "({$temp3},{$temp3})");
                            $tem2 =  $this->relajacion2($i,$j,$this->tam - 1, $this->tam - 1, 0) + $this->relajacion2(0, 0, $i, $j, 0) - $this->obtenerValorMatriz($i,$j);
                            if($tem2<=$temp2){
                                array_push($this->caminos, $this->camino);
                                array_push($pesos, $tem2);
                                $temp = $tem2;
                            }
                            $this->camino = array();

                        }
                        if($temp2 > $temp){
                            $temp2 = $temp;
                        }
                        
                    }
                }
            }
            
            $tempo = array();
            $intpesos = count($pesos);

            for($i = 0; $i < $intpesos; $i++){
                if($pesos[$i]==$temp2){
                    array_push($tempo, $this->caminos[$i]);
                }
            }
            $len = count($tempo);
            for($i = 0; $i < $len - 1; $i++){
                for($j = $i+1; $j < $len; $j++){
                    if($tempo[$i]==$tempo[$j]){
                        for($k = $j; $k < $len - 1; $k++){
                            $ayu = $tempo[$k];
                            $tempo[$k] = $tempo[$k+1];
                            $tempo[$k+1] = $ayu;
                        }
                        $len--;
                        $j--;
                    }
                }
            }
            $tempo = array_slice($tempo, 0, $len);
            $this->caminos = $tempo;
            echo '<br>';
            echo "Camino o Caminos mas cortos: ";
            echo '<br>';
            $intcami = count($this->caminos);
            $intcami2 = count(($this->caminos)[0]);
            for($i = 0; $i < $intcami; $i++){
                for($j = $intcami2 - 1 ; $j >= 0; $j--){
                    if($j==0){
                        echo ($this->caminos[$i])[$j];
                    }
                    else{
                        echo ($this->caminos[$i])[$j]."-->";
                    }
                }
                echo " Peso: ".$temp2;
                echo '<br>';
            }
        }
        public function relajacion1($X, $Y, $A, $B, $suma){
            $suma = $this->obtenerValorMatriz($X, $Y);
            if($X ==  $A && $Y ==  $B){
                return $suma;
            }
            else {
                if ($Y ==  $B) {
                    $suma = $suma + $this->relajacion1($X + 1, $Y,$A, $B, $suma);
                }
                elseif ($X ==  $A) {
                    $suma = $suma + $this->relajacion1($X, $Y + 1, $A, $B,$suma);
                }
                else {
                    if ($this->obtenerValorMatriz($X+1, $Y) <= $this->obtenerValorMatriz($X, $Y + 1)) {
                        $suma = $suma + $this->relajacion1($X + 1, $Y, $A, $B,$suma);
                    }
                    elseif ($this->obtenerValorMatriz($X , $Y+1) < $this->obtenerValorMatriz($X+1, $Y)) {
                        $suma = $suma + $this->relajacion1($X, $Y + 1, $A, $B,$suma);
                    }
                }
            }
            array_push($this->camino, "({$X},{$Y})");
            return $suma;

        }
        public function relajacion2($X, $Y, $A, $B, $suma){
            $suma = $this->obtenerValorMatriz($X, $Y);
            if($X ==  $A && $Y ==  $B){
                return $suma;
            }
            else {
                if ($Y ==  $B) {
                    $suma = $suma + $this->relajacion2($X + 1, $Y,$A, $B, $suma);
                }
                elseif ($X ==  $A) {
                    $suma = $suma + $this->relajacion2($X, $Y + 1, $A, $B,$suma);
                }
                else {
                    if ($this->obtenerValorMatriz($X+1, $Y) >= $this->obtenerValorMatriz($X, $Y + 1)) {
                        $suma = $suma + $this->relajacion2($X , $Y+1, $A, $B,$suma);
                    }
                    elseif ($this->obtenerValorMatriz($X , $Y+1) > $this->obtenerValorMatriz($X+1, $Y)) {
                        $suma = $suma + $this->relajacion2($X+1, $Y , $A, $B,$suma);
                    }
                }
            }
            array_push($this->camino, "({$X},{$Y})");
            return $suma;

        }
        public function imprimirTablero(){
            $pCasillaFila = $this->primero;
            for($i = 0; $i < $this->tam; $i++){
                $pCasillaCol = $pCasillaFila;
                echo "[";
                for($j = 0; $j < $this->tam; $j++){
                    echo $pCasillaCol->valor.",";
                    $pCasillaCol = $pCasillaCol->casillaDerecha;
                }
                echo "]";
                echo '</br>';
                $pCasillaFila = $pCasillaFila->casillaInferior;

            }
        }
        public function armarTablero(){
            $inicio = NULL;
            for($i = 0; $i < $this->tam; $i++){
                $CasillaFila = NULL;
                for($j = 0; $j <=  $this->tam; $j++){
                    $nuevaCasilla = new Casilla(rand(0,4)); 
                    $CasillaFila = $this->agregarCasillaFila($nuevaCasilla, $CasillaFila);
                }
                $inicio = $this->agregarFilaColumna($CasillaFila, $inicio);
            }
            $this->primero = $inicio;
        }
        public function agregarCasillaFila($nuev, $casi){
            if($casi == NULL){
                $casi = $nuev;
            }
            else{
                $pCasilla = $casi;
                while($pCasilla->casillaDerecha != NULL){
                    $pCasilla = $pCasilla->casillaDerecha;
                }
                $pCasilla->casillaDerecha = $nuev;
            }
            return $casi;
        }
        public function agregarFilaColumna($nuev, $casi){
            if($casi == NULL){
                $casi = $nuev;
            }
            else{
                $pCasilla = $casi;
                while($pCasilla->casillaInferior != NULL){
                    $pCasilla = $pCasilla->casillaInferior;
                }
                $pCasilla->casillaInferior = $nuev;
            }
            return $casi;
        }
    }
    $num = $_POST['n'];
    $tablero = new Tablero($num);
    $tablero->armarTablero();
    $tablero->imprimirTablero();
    $tablero->caminoCorto();
    /*$tablero = new Tablero(4);
    $fila1 = NULL;
    $fila2 = NULL;
    $fila3 = NULL;
    $fila4 = NULL;
    $columnas = null;
    $casilla00 = new Casilla(4);
    $casilla01 = new Casilla(3);
    $casilla02 = new Casilla(2);
    $casilla03 = new Casilla(4);
    $fila1 = $tablero->agregarCasillaFila($casilla00, $fila1);
    $fila1 = $tablero->agregarCasillaFila($casilla01, $fila1);
    $fila1 = $tablero->agregarCasillaFila($casilla02, $fila1);
    $fila1 = $tablero->agregarCasillaFila($casilla03, $fila1);
    $casilla10 = new Casilla(3);
    $casilla11 = new Casilla(0);
    $casilla12 = new Casilla(2);
    $casilla13 = new Casilla(4);
    $fila2 = $tablero->agregarCasillaFila($casilla10, $fila2);
    $fila2 = $tablero->agregarCasillaFila($casilla11, $fila2);
    $fila2 = $tablero->agregarCasillaFila($casilla12, $fila2);
    $fila2 = $tablero->agregarCasillaFila($casilla13, $fila2);
    $casilla20 = new Casilla(4);
    $casilla21 = new Casilla(4);
    $casilla22 = new Casilla(2);
    $casilla23 = new Casilla(1);
    $fila3 = $tablero->agregarCasillaFila($casilla20, $fila3);
    $fila3 = $tablero->agregarCasillaFila($casilla21, $fila3);
    $fila3 = $tablero->agregarCasillaFila($casilla22, $fila3);
    $fila3 = $tablero->agregarCasillaFila($casilla23, $fila3);
    $casilla30 = new Casilla(0);
    $casilla31 = new Casilla(4);
    $casilla32 = new Casilla(1);
    $casilla33 = new Casilla(2);
    $fila4 = $tablero->agregarCasillaFila($casilla30, $fila4);
    $fila4 = $tablero->agregarCasillaFila($casilla31, $fila4);
    $fila4 = $tablero->agregarCasillaFila($casilla32, $fila4);
    $fila4 = $tablero->agregarCasillaFila($casilla33, $fila4);
    $columnas = $tablero->agregarFilaColumna($fila1, $columnas);
    $columnas = $tablero->agregarFilaColumna($fila2, $columnas);
    $columnas = $tablero->agregarFilaColumna($fila3, $columnas);
    $columnas = $tablero->agregarFilaColumna($fila4, $columnas);
    $tablero->primero = $columnas;
    $tablero->imprimirTablero();
    $tablero->caminoCorto();*/
?>