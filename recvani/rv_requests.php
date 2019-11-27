<?php

abstract class base_request{

    abstract function get_method(): string;
    abstract function get_params():array;

}

class simple_interaction extends base_request{

    function __construct($uid, $eid, $score, $time){
        $this->uid = $uid;
        $this->eid = $eid;
        $this->score = $score;
        $this->time = $time;
    }

    function get_method():string{
        return "save";
    }

    function get_params():array{
        $dt = array("uid"=> $this->uid, "eid"=> $this->eid, "score"=> $this->score, "time"=> $this->time);
        return array($dt);
    }
}

class batch_interaction extends base_request{

    function __construct($interaction_arr = array()){
        $this->arr = $interaction_arr;
    }

    function append($interaction){
        array_push($this->arr,$interaction);
    }

    function clear(){
        $this->arr = array();
    }

    function get_method():string{
        return "save_batch";
    }

    function get_params():array{
        $result = array();
        foreach($this->arr as $value){
            array_push($result, $value->get_params()[0]);
        }
        return $result;

    }
}
class tag_request extends base_request {

    function __construct($eid, $tags){
        $this->eid = $eid;
        $this->tags = $tags;
    }

    function get_method():string{
        return 'set_tag';
    }
    function get_params():array{
        return array($this->eid, $this->tags);
    }
}
class exp_request extends base_request {

    function __construct($eid, $exp){
        $this->eid = $eid;
        $this->exp = $exp;
    }

    function get_method():string{
        return 'set_exp';
    }

    function get_params():array{
        return array($this->eid, $this->exp);
    }
}
class rec_request extends base_request {

    function __construct($uid, $count, $tags, $history=False ){
        $this->uid = $uid;
        $this->count = $count;
        $this->tags = $tags;
        $this->history = $history;
    }
    function get_method():string{
        return 'get_rec';
    }
    function get_params():array{
        return array(array("uid"=>$this->uid, "count"=>$this->count, "tags"=>$this->tags, "history"=>$this->history);
    }
}
class batch_param extends base_request {

    function __construct($batch_p) {
        $this->batch_p = $batch_p;
    }
    function get_method():string{
        return 'set_batch_param';
    }
    function get_params():array{
        return array($this->batch_p);
    }
}
?>

