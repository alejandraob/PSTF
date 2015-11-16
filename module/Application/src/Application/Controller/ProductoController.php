<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Entity\Producto;
use Application\Admin\Form\FormProducto\ProductoForm;

class ProductoController extends AbstractActionController
{
    protected function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }

    public function indexAction()
    {
        $em = $this->getEntityManager();
        $query  = $em->createQueryBuilder()
                ->select('a')
                ->from('Application\Entity\Producto', 'a')
                ->orderBy('a.id_producto','DESC')
                ->getQuery();
        $productos  = $query->getResult();
        return new ViewModel([
            'productos'=>$productos,
        ]);
    }

    public function nuevoAction()
    {
        //llammos a la funcion entityManager
        $em = $this->getEntityManager();
        //creamos un nuevo formulario con un entity manager
        $productoForm = new ProductoForm($em);

        $producto = new Producto();
        $productoForm->bind($producto);

        if($this->request->isPost()) {
            $productoForm->setData($this->request->getPost());
            if($productoForm->isValid()) {
                $em->persist($producto);
                $em->flush();

                $this->flashMessenger()->addSuccessMessage('Nuevo producto registrado!');
                return $this->redirect()->toRoute('index_producto');
            }
        }

        return new ViewModel([
            'form' => $productoForm,
        ]);
    }

    public function editarAction()
    {
        $id = $this->params('id');
        $em = $this->getEntityManager();
        $producto = $em->find('Application\Entity\Producto', $id);
        $productoForm = new ProductoForm($em);
        $productoForm->bind($producto);
        if($this->request->isPost()) {
            
            $productoForm->setData($this->request->getPost());
            
            if($productoForm->isValid()) {
                
                $em->persist($producto);
                $em->flush();              
            }   
        }
        return new ViewModel([
            'producto'=>$producto,
            'form'=>$productoForm,
        ]);
    }

    public function eliminarAction()
    {
        $id = $this->params('id');
        $em = $this->getEntityManager();
        $producto = $em->find('Application\Entity\Producto',$id);
        $em->remove($producto);
        $em->flush();
        
        $this->flashMessenger()->addSuccessMessage('Producto eliminado del sistema');
        return $this->redirect()->toRoute('index_producto');
    }


}
