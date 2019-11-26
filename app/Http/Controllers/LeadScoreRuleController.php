<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadScoreRuleRequest;
use App\Http\Resources\LeadScoreRulesResource;
use App\Lead;
use App\LeadScoreRule;
use Illuminate\Http\Request;

class LeadScoreRuleController extends Controller
{
    public function index()
    {
        return LeadScoreRulesResource::collection(company()->leadScoreRules()->latest()->get());
    }
    public function store(LeadScoreRuleRequest $leadScoreRuleRequest)
    {
        $condition = $leadScoreRuleRequest->condition;
        $field = $leadScoreRuleRequest->field;
        $value = $leadScoreRuleRequest->value;
        if (company()->leadScoreRules()->where([
            ['field', $field],
            ['condition', $condition],
            ['value', $value]
        ])->first())
            return error('Luật này đã tồn tại');

        if ($condition == '<' || $condition == '<=' || $condition == '>=' || $condition == '>') {
            if (company()->leadScoreRules()->where([
                ['field', $field],
                ['condition', $condition],
            ])->first())
                return error('Luật này đã tồn tại');
        }
        company()->leadScoreRules()->create($leadScoreRuleRequest->all());
        return created();
    }
    public function update(LeadScoreRuleRequest $leadScoreRuleRequest, LeadScoreRule $leadScoreRule)
    {
        $condition = $leadScoreRuleRequest->condition;
        $field = $leadScoreRuleRequest->field;
        $value = $leadScoreRuleRequest->value;
        $check = company()->leadScoreRules()->where([
            ['field', $field],
            ['condition', $condition],
            ['value', $value]
        ])->first();
        if ($check && $check->id = $leadScoreRule->id) error('Luật này đã tồn tại');
        if ($condition == '<' || $condition == '<=' || $condition == '>=' || $condition == '>') {
            $check = company()->leadScoreRules()->where([
                ['field', $field],
                ['condition', $condition],
            ])->first();
            if ($check && $check->id = $leadScoreRule->id) error('Luật này đã tồn tại');
        }
        $leadScoreRule->update($leadScoreRuleRequest->all());
        return updated();
    }
    public function destroy(LeadScoreRule $leadScoreRule)
    {
        delete($leadScoreRule);
    }
    public function test(Lead $lead)
    {

        $score = 0;
        $rules = company()->leadScoreRules;
        foreach ($rules as $rule) {
            $curentValue = $lead[$this->convertField($rule['field'])];
            if ($this->compare($curentValue, $rule['condition'], $rule['value'])) {
                if ($rule['action']) $score += $rule['score'];
                else $score -= $rule['score'];
            }
        }
        $lead->score = $score;
    }
}
