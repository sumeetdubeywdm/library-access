<?php

class LibraryAccess_ProductListAccess{

    public static function getLibraryProductsList(){
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_library',
                    'value' => 'yes',
                ),
            ),
        );

        return get_posts($args);
    }

}