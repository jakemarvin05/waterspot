<?php

class SchedulerShell extends AppShell {
	//public $tasks = array('SlotExpirationWarningEmail');
	//public $tasks = array('FreeExpiredSlots');
	public $tasks = array('Wow');

    public function main() {
        //$this->SlotExpirationWarningEmail->execute();
        //$this->FreeExpiredSlots->execute();
        $this->out($this->Wow->execute());
    }
}