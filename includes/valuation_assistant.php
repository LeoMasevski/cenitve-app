<?php

function analyze_valuation(array $valuation): array
{
    $warnings = [];
    $recommendations = [];
    $filled_fields = 0;
    $total_fields = 6;

    $required_fields = [
        'client_name',
        'client_address',
        'valuation_purpose',
        'value_basis',
        'value_premise',
        'first_inspection_at'
    ];

    foreach ($required_fields as $field) {
        if (!empty($valuation[$field])) {
            $filled_fields++;
        }
    }

    $completeness = (int) round(($filled_fields / $total_fields) * 100);

    if (!empty($valuation['first_inspection_at'])) {
        $inspection_time = strtotime($valuation['first_inspection_at']);

        if ($inspection_time !== false && $inspection_time < time()) {
            $warnings[] = 'Datum prvega ogleda je v preteklosti.';
        }
    }

    if (
        ($valuation['valuation_purpose'] ?? '') === 'zavarovano posojanje'
        && ($valuation['value_basis'] ?? '') !== 'tržna vrednost'
    ) {
        $warnings[] = 'Pri zavarovanem posojanju je običajno smiselno preveriti uporabo tržne vrednosti.';
    }

    if (
        ($valuation['valuation_purpose'] ?? '') === 'stečajni postopek'
        && ($valuation['value_premise'] ?? '') !== 'redna likvidacija'
    ) {
        $recommendations[] = 'Pri stečajnem postopku je priporočljivo preveriti, ali je premisa vrednosti ustrezna.';
    }

    if (
        ($valuation['valuation_purpose'] ?? '') === 'sodni postopek'
        && ($valuation['value_basis'] ?? '') === 'likvidacijska vrednost'
    ) {
        $recommendations[] = 'Pri sodnem postopku preverite, ali je izbrana podlaga vrednosti skladna z namenom cenitve.';
    }

    if (!empty($valuation['client_address']) && mb_strlen($valuation['client_address']) < 8) {
        $warnings[] = 'Naslov naročnika je zelo kratek, zato je morda nepopoln.';
    }

    if ($completeness === 100 && empty($warnings)) {
        $status = 'Dobro izpolnjeno';
    } elseif ($completeness >= 80 && count($warnings) <= 1) {
        $status = 'Potrebno preverjanje';
    } else {
        $status = 'Potrebna dopolnitev';
    }

    return [
        'completeness' => $completeness,
        'status' => $status,
        'warnings' => $warnings,
        'recommendations' => $recommendations
    ];
}