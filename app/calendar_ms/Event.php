<?php


class Event {

    private $id;
    private $title;
    private $start_event;
    private $end_event;

    public function __construct($id, $title, $start_event, $end_event) {
        $this->id = $id;
        $this->title = $title;
        $this->start_event = $start_event;
        $this->end_event = $end_event;
    }

    public function getid() {
        return $this->id;
    }

    public function gettitle() {
        return $this->title;
    }

    public function getstart_event() {
        return $this->start_event;
    }

    public function getend_event() {
        return $this->end_event;
    }


}