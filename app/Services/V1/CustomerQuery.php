<?php

namespace App\Services\V1;

use Illuminate\Http\Request;

class CustomerQuery {
  protected $allowedParams = [
    'name' => ['eq'],
    'type' => ['eq'],
    'email' => ['eq'],
    'address' => ['eq'],
    'city' => ['eq'],
    'state' => ['eq'],
    'postalCode' => ['eq', 'gt', 'lt'],
  ];

  protected $columnMap = [
    'postalCode' => 'postal_code'
  ];

  protected $operatorMap = [
    'eq' => '=',
    'lt' => '<',
    'lte' => '<=',
    'gt' => '>',
    'gte' => '>='
  ];

  public function transform(Request $request) {
    $eloquentQuery = [];

    foreach ($this->allowedParams as $param => $operators) {
      $query = $request->query($param);
      // dd($request->query('type'));

      // if (!isset($query)) {
      //   continue;
      // }

      $column = $this->columnMap[$param] ?? $param;
      // dd($column);

      foreach($operators as $operator) {
        // dd($query[$operator]);
        if (isset($query[$operator])) {
          $mappedOperator = $this->operatorMap[$operator];
          $value = $query[$operator];
          $eloquentQuery[] = [$column, $mappedOperator, $value];
          // array_push($eloquentQuery, [$column, $mappedOperator, $value]);
        }
      }

    }
    // dd($eloquentQuery);

    return $eloquentQuery;
  }
}