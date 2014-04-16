<?php

namespace SS6\ShopBundle\Controller\Admin;

use APY\DataGridBundle\Grid\Action\RowAction;
use APY\DataGridBundle\Grid\Column\BooleanColumn;
use APY\DataGridBundle\Grid\Grid;
use APY\DataGridBundle\Grid\Row;
use APY\DataGridBundle\Grid\Source\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SS6\ShopBundle\Form\Admin\Product\ProductFormType;
use SS6\ShopBundle\Model\Product\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller {
	
	/**
	 * @Route("/product/edit/{id}", requirements={"id" = "\d+"})
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 * @param int $id
	 */
	public function editAction(Request $request, $id) {
		$form = $this->createForm(new ProductFormType());
						
		try {
			$result = $this->get('ss6.shop.product.product_edit_facade')->edit($id, $request, $form);

			if ($result) {
				$this->get('session')->getFlashBag()->add(
					'success', 'Produkt byl úspěšně upraven.'
				);
				return $this->redirect($this->generateUrl('admin_product_edit', array('id' => $id)));
			}
		} catch (\SS6\ShopBundle\Model\Product\Exception\ProductNotFoundException $e) {
			throw $this->createNotFoundException($e->getMessage(), $e);
		} catch (\SS6\ShopBundle\Exception\ValidationException $e) {
			$this->get('session')->getFlashBag()->add(
				'error', $e->getMessage()
			);
		}
		
		return $this->render('@SS6Shop/Admin/Content/Product/edit.html.twig', array(
			'form' => $form->createView(),
			'product' => $form->getData(),
		));
	}
	
	/**
	 * @Route("/product/new/")
	 * @param \Symfony\Component\HttpFoundation\Request $request
	 */
	public function newAction(Request $request) {
		$form = $this->createForm(new ProductFormType());
						
		try {
			$result = $this->get('ss6.shop.product.product_edit_facade')->create($request, $form);

			if ($result) {
				$this->get('session')->getFlashBag()->add(
					'success', 'Produkt byl úspěšně vytvořen.'
				);
				return $this->redirect($this->generateUrl('admin_product_edit', array('id' => $form->getData()->getId())));
			}
		} catch (\SS6\ShopBundle\Model\Product\Exception\ProductNotFoundException $e) {
			throw $this->createNotFoundException($e->getMessage(), $e);
		} catch (\SS6\ShopBundle\Exception\ValidationException $e) {
			$this->get('session')->getFlashBag()->add(
				'error', $e->getMessage()
			);
		}
		
		return $this->render('@SS6Shop/Admin/Content/Product/new.html.twig', array(
			'form' => $form->createView(),
		));
	}
	
	/**
	 * @Route("/product/list/")
	 */
	public function listAction() {
		$source = new Entity(Product::class);
				
		$grid = $this->get('grid');
		/* @var $grid Grid */
		$grid->setSource($source);
		
		$grid->getColumns()->addColumn(new BooleanColumn(array(
			'id' => 'visible',
			'filterable' => false,
			'sortable' => false,
		)));
		
		$grid->setVisibleColumns(array('visible', 'name', 'price'));
		$grid->setColumnsOrder(array('visible', 'name', 'price'));
		$grid->getColumns()->getColumnById('visible')->setTitle('Viditelné');
		$grid->getColumns()->getColumnById('name')->setTitle('Název');
		$grid->getColumns()->getColumnById('price')->setTitle('Cena');
		
		$grid->hideFilters();
		$grid->setActionsColumnTitle('Akce');
		$grid->setDefaultOrder('name', 'asc');
		$grid->setLimits(array(2, 20));
		$grid->setDefaultLimit(20);
		
		$detailRowAction = new RowAction('Upravit', 'admin_product_edit');
		$detailRowAction->setRouteParameters(array('id'));
		$grid->addRowAction($detailRowAction);
		
		$deleteRowAction = new RowAction('Smazat', 'admin_product_delete', true);
		$deleteRowAction->setConfirmMessage('Opravdu si přejete zboží smazat?');
		$deleteRowAction->setRouteParameters(array('id'));
		$grid->addRowAction($deleteRowAction);
		
		$repository = $this->getDoctrine()->getRepository(Product::class);
		$source->manipulateRow(function (Row $row) use ($repository) {
			$product = $repository->find($row->getField('id'));
			$row->setField('visible', $product->isVisible());
			
			return $row;
		});
		
		return $grid->getGridResponse('@SS6Shop/Admin/Content/Product/list.html.twig');
	}
	
	/**
	 * @Route("/product/delete/{id}", requirements={"id" = "\d+"})
	 * @param int $id
	 */
	public function deleteAction($id) {
		try {
			$this->get('ss6.shop.product.product_edit_facade')->delete($id);

			$this->get('session')->getFlashBag()->add(
				'success', 'Produkt byl úspěšně smazán.'
			);
		} catch (\SS6\ShopBundle\Model\Product\Exception\ProductNotFoundException $e) {
			throw $this->createNotFoundException('Product to delete not found.', $e);
		}
		
		return $this->redirect($this->generateUrl('admin_product_list'));
	}
}
