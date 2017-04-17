<?php    

    /**
     * Get the correct icon for an item
     * @param  int    $item_id 
     * @return string      
     */
    function generateIcon(int $item_id): string
    {
        $url = "https://image.eveonline.com/Type/" . $item_id . "_32.png";
        return $url;
    }

    /**
     * Inject icons into a result array or object
     * @param  [array|stdClass]  $dataset 
     * @param  boolean $type    
     * @return array   
     */
    function injectIcons($dataset, $type = false) : array
    {
        $max = count($dataset);
        if ($max > 0) {
            for ($i = 0; $i < $max; $i++) {
                if ($type == "object") {
                    $dataset[$i]->url = generateIcon((int) $dataset[$i]->item_id);
                } else {
                    $dataset[$i]['url'] = generateIcon((int) $dataset[$i]['item_id']);
                }
            }
        }
        return $dataset;
    }
