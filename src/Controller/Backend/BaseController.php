<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2019-04-20
 * Time: 16:26
 */

namespace App\Controller\Backend;


use App\Repository\BaseRepository;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class BaseController extends AbstractController {
    public const DEFAULT_PER_PAGE = 10;

    /**
     * @var PaginatorInterface
     */
    protected PaginatorInterface $paginator;
    /**
     * @var FilterBuilderUpdaterInterface
     */
    private FilterBuilderUpdaterInterface $filterBuilderUpdater;

    public function __construct(PaginatorInterface $paginator, FilterBuilderUpdaterInterface $filterBuilderUpdater) {
        $this->paginator = $paginator;
        $this->filterBuilderUpdater = $filterBuilderUpdater;
    }

    protected function paginate(Query $query, Request $request, array $options = []): PaginationInterface {
        return $this->paginator->paginate(
            $query,
            $request->query->getInt( 'page', 1),
            $request->getSession()->get( 'per-page') ?? static::DEFAULT_PER_PAGE,
            $options
        );
    }

    protected function paginateWithFiltering(BaseRepository $repository, Request $request, FormInterface $filterForm, array $options = []): PaginationInterface {
        $queryBuilder = $repository->createQueryBuilder('o');

        if ($request->query->has($filterForm->getName())) {
            $filterForm->submit($request->query->get($filterForm->getName()));
            $this->filterBuilderUpdater->addFilterConditions($filterForm, $queryBuilder);
        }

        return $this->paginate(
            $queryBuilder->getQuery(),
            $request,
            $options,
        );
    }
}