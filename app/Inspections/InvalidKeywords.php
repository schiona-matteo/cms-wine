<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    /**
     * All registered invalid keywords.
     *
     * @var array
     */
    protected $keywords = [
        'yahoo customer support',
        '#1',
        '100% more',
        '100% free',
        '100% satisfied',
        'Additional income',
        'Be your own boss',
        'Best price',
        'Big bucks',
        'Billion',
        'Cash bonus',
        'Cents on the dollar',
        'Consolidate debt',
        'Double your cash',
        'Double your income',
        'Earn extra cash',
        'Earn money',
        'Eliminate bad credit',
        'Extra cash',
        'Extra income',
        'Expect to earn',
        'Fast cash',
        'Financial freedom',
        'Free access',
        'Free consultation',
        'Free gift',
        'Free hosting',
        'Free info',
        'Free investment',
        'Free membership',
        'Free money',
        'Free preview',
        'Free quote',
        'Free trial',
        'Full refund',
        'Get out of debt',
        'Get paid',
        'Giveaway',
        'Guaranteed',
        'Increase sales',
        'Increase traffic',
        'Incredible deal',
        'Lower rates',
        'Lowest price',
        'Make money',
        'Million dollars',
        'Miracle',
        'Money back',
        'Once in a lifetime',
        'One time',
        'Pennies a day',
        'Potential earnings',
        'Prize',
        'Promise',
        'Pure profit',
        'Risk-free',
        'Satisfaction guaranteed',
        'Save big money',
        'Save up to',
        'Special promotion',
        'XXX',
        'sex',
        'porn',
    ];

    /**
     * Detect spam.
     *
     * @param  string $body
     * @throws \Exception
     */
    public function detect($body)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Your reply contains spam.');
            }
        }
    }
}
