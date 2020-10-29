<?php
    /**
     * Passe d'une date écrite sous la forme yyyy-mm-dd à une date écrite sous la forme dd/mm/yyyy
     */
    function formaliserDate($date){
        $dateSplit = explode("-", $date);
        return $dateSplit[2] . "/" . $dateSplit[1] . "/" . $dateSplit[0];
    }

?>