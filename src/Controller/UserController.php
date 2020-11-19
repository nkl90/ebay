<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Goods;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Common\Collections\Expr\Value;

Class UserController extends AbstractController

{
    private $entityManager;
       
    public function cabinet(EntityManagerInterface $entityManager)
    {
        $goodsname=array();
        $goodsid=array();
        $goodsdesc=array();
        $num=4;
        $this->entityManager=$entityManager;
        $rsm= New ResultSetMapping();
        $rsm->addScalarResult('GoodsName', 'GoodsName');
        $rsm->addScalarResult('gid', 'gid');
        $rsm->addScalarResult('Description', 'Description');
       
        $query = $entityManager->createNativeQuery('
        SELECT *
        FROM goods
        ' , $rsm);
        
        $goods = $query->getResult();
        $page = isset($_GET['page']) ? $_GET['page']:1;
    
        for ($linksgoods=$num*$page-$num; $linksgoods<$num*$page;$linksgoods++)
        {
            if (isset($goods[$linksgoods]))
            {
                array_push($goodsid,$goods[$linksgoods]['gid']);
                array_push($goodsname,$goods[$linksgoods]['GoodsName']);
                array_push($goodsdesc,$goods[$linksgoods]['Description']);
                echo '<br>';
            }
        }
         $cicle=count($goodsname);
        $total = intval(((count($goods) - 1) / 5) + 1);
        $nav=$this->page_navigation(count($goods),'cabinet');
        return $this->render('Cabinet/index.html.twig',['goodsname' => $goodsname,'Description'=>$goodsdesc,'goodsid'=>$goodsid]);
    }

    
    
    public function addgoods()
    {
    return $this->render('goods/goods.html.twig');
    }

    public function mygoods(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager)
    { 
        $lastUsername = $authenticationUtils->getLastUsername();
        $goodsname=array();
        $goodsid=array();
        $num=5;
    $this->entityManager=$entityManager;
    $rsm= New ResultSetMapping();
    $rsm->addScalarResult('GoodsName', 'GoodsName');
    $rsm->addScalarResult('gid', 'gid');
    $uid=$this->getUid($authenticationUtils, $entityManager);
    $query = $entityManager->createNativeQuery('
    SELECT *
    FROM goods
    where uid=?
    ' , $rsm);
    $query->setParameter(1, $uid);
    $goods = $query->getResult();
    if (count($goods)!=0){
    $page = isset($_GET['page']) ? $_GET['page']:1;
    
    for ($linksgoods=$num*$page-$num; $linksgoods<$num*$page;$linksgoods++)
        {
            if (isset($goods[$linksgoods]))
            {
            array_push($goodsid,$goods[$linksgoods]['gid']);
            array_push($goodsname,$goods[$linksgoods]['GoodsName']);
            echo '<br>';
            }
        }
    $cicle=count($goodsname);
    $total = intval(((count($goods) - 1) / 5) + 1);
    $nav=$this->page_navigation(count($goods),'mygoods');
    return  $this->render('goods/mygoods.html.twig',['goodsreference' => $goodsname,'cicle'=>$cicle,'goodsid'=>$goodsid]);
    }
    else 
        return $this->render('goods/mygoods.html.twig');
    }

    public function reggoods(AuthenticationUtils $authenticationUtils,  EntityManagerInterface $entityManager)
    { $lastUsername = $authenticationUtils->getLastUsername();
    $this->entityManager=$entityManager;
    $addgoods= new Goods();
    $uid=$this->getUid($authenticationUtils, $entityManager);
    $addgoods->setUid($uid);
    $addgoods->setGoodsname($_POST['goodsname']);
    $addgoods->setPrice($_POST['goodsprice']);
    $addgoods->setDescription($_POST['memos']);
    $creategoods=$this->getDoctrine()->getManager();
    $creategoods->persist($addgoods);
    $creategoods->flush();
    return $this->render('goods/sgoods.html.twig');
    }
    
    
    public function getUid(AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManager) {
        $lastUsername = $authenticationUtils->getLastUsername();
        $rsm= New ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $query = $entityManager->createNativeQuery('
    SELECT id
    FROM users
    where email=?
    ' , $rsm);
        $query->setParameter(1, $lastUsername);
        
        $userid = $query->getResult();
        $uid=implode(',', $userid[0]);
        return $uid;
    }
    function page_navigation($posts,$link){
        $page = isset($_GET['page']) ? $_GET['page']:1;
        $num = 5;
        $page = isset($_GET['page']) ? $_GET['page']:1;
        $total = intval((($posts- 1) / $num) + 1);
        if ($page != 1) $pervpage = '<a href=mygoods?page=1><<</a>
                                 <a href='.$link.'?page='. ($page - 1) .'><</a> ';
        else
            $pervpage="";
            if ($page != $total) $nextpage = ' <a href='.$link.'?page='. ($page + 1) .'>></a>
                                   <a href=mygoods?page=' .$total. '>>></a>';
            else
                $nextpage="";
                
                // Находим две ближайшие станицы с обоих краев, если они есть
                if($page - 2 > 0) $page2left = ' <a href='.$link.'?page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';
                else
                    $page2left='';
                    if($page - 1 > 0) $page1left = '<a href='.$link.'?page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';
                    else
                        $page1left='';
                        if($page + 2 <= $total) $page2right = ' | <a href='.$link.'?page='. ($page + 2) .'>'. ($page + 2) .'</a>';
                        else
                            $page2right="";
                            if($page + 1 <= $total) $page1right = ' | <a href='.$link.'?page='. ($page + 1) .'>'. ($page + 1) .'</a>';
                            else
                                $page1right="";
                                
                                
                                // Вывод меню
                                echo  ($pervpage) , ($page2left) , ($page1left),'<b>' , ($page) ,'</b>', ($page1right) , ($page2right) , ($nextpage);
    }
    
    Public function ShowGoods(EntityManagerInterface $entityManager) {
        $this->entityManager=$entityManager;
        $rsm= New ResultSetMapping();
        $rsm->addScalarResult('GoodsName', 'GoodsName');
        $rsm->addScalarResult('Price', 'Price');
        $rsm->addScalarResult('Description', 'Description');
        $gid=$_GET['gid'];
        $query = $entityManager->createNativeQuery('
    SELECT GoodsName,Description, Price
    FROM goods
    where gid=?
    ' , $rsm);
        $query->setParameter(1, $gid);
        $goods = $query->getResult();
        
        return $this->render('goods/showgoods.html.twig',['goodsname' => $goods[0]['GoodsName'],'Description'=>$goods[0]['Description'],'Price'=>$goods[0]['Price']]);
        
    }
}