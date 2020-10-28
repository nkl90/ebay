<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Contract;

class DefaultController extends AbstractController
{

    public function index()
    {
        /*$contract = $this->getDoctrine()->getManager()->getRepository(Contract::class)->findOneById(1);
        return $this->render('contract/annex_1_preliminary_agreement.html.twig', [
            'contract' => $contract,
            'contract_sum' => $contract->getSum(),
            'terms_of_payment' => '[Дополнительные условия оплаты]',
            'contract_date' => new \DateTime('now')
        ]);*/
        
        return $this->redirectToRoute('sonata_admin_dashboard');
        
    }
    
}