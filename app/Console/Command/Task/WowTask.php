<?php

class WowTask extends Shell {
	public $uses = array('Hello');
    public function execute() {
        $schedule_log = fopen('Schedule.log', 'a');
        fwrite($schedule_log, 'testing complete --- ' . date('Y-m-D h:i:sa') . "\n");
        fclose($schedule_log);
        return 'ewan haha!';
    }
}
