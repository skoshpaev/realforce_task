# realforce_task

To install - start install.sh with  
```sh install.sh```  - WARNING: it can be a little long to wait installing composer dependencies
because of not proper working of docker containers. It will be fixed in next versions.

To test start test.sh with   
```sh test.sh```

To check an API   
```POST / HTTP/1.1
   Host: 0.0.0.0
   Content-Type: application/json
   Content-Length: 89
   
   {
       "age": 66,
       "kids_number": 3,
       "has_company_car": true,
       "salary": 10000
   }
```

All the code is placed in `App\Component\SalaryValidator`, `App\Controller\CalculatorController` and `App\DTO\EmployeeDTO`