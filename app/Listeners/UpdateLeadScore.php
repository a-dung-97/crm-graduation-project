<?php

namespace App\Listeners;

use App\Company;
use App\Events\LeadOrRulesChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UpdateLeadScore
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  LeadOrRulesChanged  $event
     * @return void
     */
    public function handle(LeadOrRulesChanged $event)
    {
        if ($event->lead)  $this->updateScore($event->lead, false);
        else {
            $leads = company()->leads;
            foreach ($leads as $lead) $this->updateScore($lead, true);
        }
    }
    private function updateScore($lead, $updateAll)
    {
        $score = 0;
        $rules = Company::find($lead->company_id)->leadScoreRules;
        foreach ($rules as $rule) {
            $curentValue = $lead[$this->convertField($rule['field'])];
            if ($this->compare($curentValue, $rule['condition'], $rule['value'])) {
                if ($rule['action']) $score += $rule['score'];
                else $score -= $rule['score'];
            }
        }
        if ($score < 0) $score = 0;
        if ($updateAll) $lead->update(['score' => $score]);
        else
            $lead->score = $score;
    }
    private function convertField($val)
    {
        switch ($val) {
            case 'Nguồn':
                return 'source_id';
                break;
            case 'Doanh thu':
                return 'revenue';
                break;
            case 'Tổng số nhân viên':
                return 'number_of_workers';
                break;
            case 'Ngành nghề':
                return 'branch_id';
                break;
            default:
                break;
        }
    }
    private function compare($curentValue, $condition, $value)
    {
        switch ($condition) {
            case '=':
                return ($curentValue == $value) ? true : false;
                break;
            case '!=':
                return ($curentValue != $value) ? true : false;
                break;
            case '<':
                return ($curentValue < $value) ? true : false;
                break;
            case '<=':
                return ($curentValue <= $value) ? true : false;
                break;
            case '>':
                return ($curentValue > $value) ? true : false;
                break;
            case '>=':
                return ($curentValue >= $value) ? true : false;
                break;
            case 'bỏ trống':
                return ($curentValue == null) ? true : false;
                break;
            case 'không bỏ trống':
                return ($curentValue != null) ? true : false;
                break;
            default:
                break;
        }
    }
}
