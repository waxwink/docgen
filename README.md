# DocGen
If you need a minimal API documentation quickly for your front end team, this package might help you.
 
## Instruction
For installing just use the following command:
```
composer require waxwink/docgen
```
After that publish the necessary js ans css files to public folder for the documentation view:
```
php artisan vendor:publish --tag=public
```
This package can now work by going to `/routes` url but for more complete api documentation you need to follow some rules in your laravel app:

### Request Body
For including the request body of each route to the documentation you must use a FormRequest object as a input parameter in the related controller.
DocGen will use that request object to resolve the request body and the rules will be shown exactly like what is written in the rules method of the request object.
 
 
 ### Output Keys
 For including the output keys of a route every route should use a JsonResource to show the output.
 DocGen must be aware of this resource by put it as doc block (with `@DG-Resource`) in the controller method as follows:
 
 ```php
     /**
      * @DG-Resource App\Http\Resources\OrderResource
      *
      * @param OrderRequest $request
      * @return AnonymousResourceCollection
      */
     public function index(OrderRequest $request)
     {
         //....
     }
 ```