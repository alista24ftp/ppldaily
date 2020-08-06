<?php
namespace App\Traits;

trait NestedTree
{
    public static function buildTree($arr, $parent_id_key='parent_id', $id_key='id')
    {
        $lookup = [];
        $rootNodes = [];
        foreach($arr as $item){
            $node = [
                'children' => [],
                //'parent' => null,
                'item' => $item,
            ];
            if(array_key_exists($item[$id_key], $lookup)){
                // if already found as parent, register item object
                $lookup[$item[$id_key]]['item'] = $item;
                $node = $lookup[$item[$id_key]];
            }else{
                $lookup[$item[$id_key]] = $node;
            }

            if(!$item[$parent_id_key]){
                // is root node
                //array_push($rootNodes, $node);
                $rootNodes[] = &$lookup[$item[$id_key]];
            }else{
                // is a child
                $parentNode = [
                    'children' => [],
                    //'parent' => null,
                    'item' => null,
                ];
                if(!array_key_exists($item[$parent_id_key], $lookup)){
                    // unknown parent, create preliminary parent node
                    $lookup[$item[$parent_id_key]] = $parentNode;
                }else{
                    $parentNode = $lookup[$item[$parent_id_key]];
                }
                //array_push($parentNode['children'], $node);
                $lookup[$item[$parent_id_key]]['children'][] = &$lookup[$item[$id_key]];
                //$node['parent'] = $parentNode;
                //$lookup[$item[$id_key]]['parent'] = $lookup[$item[$parent_id_key]];
            }
        }

        /*
        return array_filter($rootNodes, function($node){
            return !is_null($node['item']);
        });
        */
        foreach($rootNodes as $i => $rn){
            if(!$rn['item']){
                unset($rootNodes[$i]);
            }
        }
        return $rootNodes;
    }
}
