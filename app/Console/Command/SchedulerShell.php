<?php

class SchedulerShell extends AppShell {
	public $tasks = array('WarnUnpaidUsers', 'FreeExpiredSlots', 'MinToGoCheck');

    public function main() {
        $this->MinToGoCheck->execute();
        // $log = "*--------------------------------------------------------------------------------\n";
        // $log .= "Scheduled task run at : " . date('Y-m-d H:i:s') . " \n";
        // $log .= "Run WarnUnpaidUsers function\n";
        // $log .= $this->WarnUnpaidUsers->execute();
        // $log .= "Run FreeExpiredSlots function\n";
        // $log .= $this->FreeExpiredSlots->execute();
        // $schedule_log = fopen('Schedule.log', 'a');
        // fwrite($schedule_log, $log . "\n");
        // fclose($schedule_log);

        // code for the min-to-go
        
    }
}