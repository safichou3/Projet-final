<?php
namespace App\Service\Search;

use App\Repository\RecipeRepository;
use App\Service\Pagination\ApiPagination;
use Symfony\Component\HttpFoundation\Request;

class RecipeSearch
{
    private int $itemPerPage = 12;
    private int $page;
    private int $limit;
    private string $all;
    private string $order;
    private string $orderby;
    private RecipeRepository $repo;

    public function __construct(RecipeRepository $reciperepo)
    {
        $this->repo = $reciperepo;
    }

    public function request(Request $request)
    {
        $this->page = abs($request->get('page', 1));
        $this->limit = $request->get('limit', $this->itemPerPage);
        $this->all = $request->get('all', 'no'); // no (default) / yes
        $this->order = $request->get('order', 'asc');
        $this->orderby = $request->get('orderby', 'title');

        if ($this->all === 'yes') {
            $data = $this->getAllRecipes();
        } else {
            $data = $this->getRecipesPaginate();
        }
        return $data;
    }

    private function getAllRecipes()
    {
        $cats = $this->repo->findAllOrdered($this->order);
        return $this->prepareArray(null, $cats);
    }

    private function getRecipesPaginate()
    {
        $count = $this->repo->countAll();
        $recipes = $this->repo->findAllOrderedPaginate($this->page, $this->limit, $this->order);
        $pagination = new ApiPagination($this->page, $this->limit, $count, $this->orderby, $this->order);
        return $this->prepareArray($pagination, $recipes);
    }

    private function prepareArray(?ApiPagination $pagination, $recipes): array
    {
        if ($pagination === null) {
            $json = null;
        } else {
            $json = $pagination->getJsonpagination();
        }
        return [
            'pagination' => $json,
            'recipes'   => $recipes,
        ];
    }
}
