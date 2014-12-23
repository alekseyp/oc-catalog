<?php namespace Tiipiik\Catalog\Components;

use Cms\Classes\ComponentBase;
use Tiipiik\Catalog\Models\Product as ProductModel;

class ProductDetails extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name'        => 'ProductDetails',
            'description' => 'Details of displayed product'
        ];
    }

    public function defineProperties()
    {
        return [
            'idParam' => [
                'title'       => 'Slug param name',
                'description' => 'The URL route parameter used for looking up the product by its slug.',
                'default'     => ':slug',
                'type'        => 'string'
            ],
        ];
    }

    public function onRun()
    {
        $loadProduct = $this->loadProduct();
        
        if (!$loadProduct)
        {
            // The line below works but return a line of details
            //return Response::make( $this->controller->run('404'), 404 );
            // Use this instead
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }
        
        $this->product = $this->page['product'] = $loadProduct;
        
        $this->page->title = $this->product->title;
        $this->page->description = $this->product->description;
    }

    protected function loadProduct()
    {
        $slug = $this->propertyOrParam('idParam');
        return ProductModel::where('slug', '=', $slug)->first();
    }

}