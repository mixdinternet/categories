## Categorias

Pacote para implementar categorias em seus pacotes.

## Instalação

Adicione no seu composer.json

```js
  "require": {
    "mixdinternet/categories": "0.1.*"
  }
```

ou

```js
  composer require mixdinternet/categories
```

## Service Provider

Abra o arquivo `config/app.php` e adicione

`Mixdinternet\Categories\Providers\CategoriesServiceProvider::class`

## Migrations

```
  php artisan vendor:publish --provider="Mixdinternet\Categories\Providers\CategoriesServiceProvider" --tag="migrations"`
  php artisan migrate
```

## Configurações

É possivel a troca de icone e nomenclatura do pacote em `config/mcategories.php`

```
  php artisan vendor:publish --provider="Mixdinternet\Categories\Providers\CategoriesServiceProvider" --tag="config"`
```

## Utilização

* Acrescentar diretorios e arquivos ao pacote em que deseja implementar categorias

Como exemplo usaremos um pacote com o nome Flavor.<br>
Após esses passos e necessario criar o diretorio Scopes dentro do pacote em que deseja implementar a categoria. <br>
E dentro deste diretorio Scopes um arquivo NomePacoteCategory.php <br>
Ex: FlavorsCategory.php que contera o seguinte codigo 


```
    <?php
    
    namespace Mixdinternet\Flavors\Scopes;
    
    use Illuminate\Database\Eloquent\Scope;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Database\Eloquent\Builder;
    
    class FlavorsCategory implements Scope
    {
        /**
         * Apply the scope to a given Eloquent query builder.
         *
         * @param  \Illuminate\Database\Eloquent\Builder  $builder
         * @param  \Illuminate\Database\Eloquent\Model  $model
         * @return void
         */
        public function apply(Builder $builder, Model $model)
        {
            $builder->where('type', 'flavors');
        }
    }
```
Nesses trechos trocar de acordo com o nome do seu pacote

```
namespace Mixdinternet\Flavors\Scopes;
```

```
 class FlavorsCategory implements Scope
 ```
 
 ```
 $builder->where('type', 'flavors');
 ```
 
 * Na pasta base do seu pacote criar o arquivo Category.php contendo o seguinte codigo

```
<?php
 
 namespace Mixdinternet\Flavors;
 
 use Mixdinternet\Categories\Category as BaseCategory;
 use Mixdinternet\Flavors\Scopes\FlavorsCategory;
 
 class Category extends BaseCategory
 {
     public static function boot()
     {
         static::addGlobalScope(new FlavorsCategory());
     }
 
     public function flavors()
     {
         return $this->hasMany(Flavor::class);
     }
 }
 ```
 
 * Sera necessario alterar de acordo com o nome do seu pacote nos seguintes trechos
 
 trocar o namespace de acordo com o seu pacote
 ```
 namespace Mixdinternet\Flavors;
 ```
 
 Incluir o arquivo anteriormente criado
 ```
 use Mixdinternet\Flavors\Scopes\FlavorsCategory;
 ```

 
 ```
 static::addGlobalScope(new FlavorsCategory());
 ```
 
 Este e o relacionamento com o seu pacote
 ```
public function flavors()
{
  return $this->hasMany(Flavor::class);
}
 ```
 
 ### Arquivo de rotas do pacote
 
 Acrescentar em seu arquivo de rotas e substituir ``` {categoryType} ``` pelo nome de seu pacote.

``` 
<?php
Route::group(['middleware' => ['web'], 'prefix' => config('admin.url'), 'as' => 'admin.flavors'], function () {
    Route::group(['middleware' => ['auth.admin', 'auth.rules']], function () {

        Route::get('{categoryType}/categories/trash', ['uses' => '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController@index', 'as' => '.categories.trash']);
        Route::post('{categoryType}/categories/restore/{id}', ['uses' => '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController@restore', 'as' => '.categories.restore']);
        Route::resource('{categoryType}/categories', '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController', [
            'names' => [
                'index' => '.categories.index',
                'create' => '.categories.create',
                'store' => '.categories.store',
                'edit' => '.categories.edit',
                'update' => '.categories.update',
                'show' => '.categories.show',
            ], 'except' => ['destroy']]);
        Route::delete('{categoryType}/categories/destroy', ['uses' => '\Mixdinternet\Categories\Http\Controllers\CategoriesAdminController@destroy', 'as' => '.categories.destroy']);
       
    });
});
 ```