<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="el">
    <head profile="http://gmpg.org/xfn/11">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title></title>
    </head>
    <body><?php
        require_once('wp-load.php') ;

               function clean_useless_posts() {
            global $wpdb ;
            echo '<pre>' ;
            $blogs = $wpdb->get_results( "SELECT path , blog_id FROM wp_blogs WHERE (last_updated  > NOW() - INTERVAL 52 WEEK AND last_updated  < NOW() - INTERVAL 30 WEEK )" , ARRAY_A ) ;

            $i = 0 ;
            echo '<pre>' ;
            echo count( $blogs ) . '<br/>' ;

            echo 'blog_id, blog_url, db, posts on blog<br/>' ;
            foreach ( $blogs as $blog ) {
                                $posts = $wpdb->get_results( "SELECT * FROM {$wpdb->base_prefix}{$blog[ 'blog_id' ]}_posts where "
                        . "post_status = 'auto-draft' OR "
                        . "post_status = 'revision' OR"
                        . " post_status='trash' and post_modified < NOW() - INTERVAL 10 WEEK" , ARRAY_A ) ;

                if ( count( $posts ) > 0 ) {
                    echo '<br/>' . $blog[ 'blog_id' ] . ', http://blogs.sch.gr' . $blog[ 'path' ] . ', ' . substr( md5( $blog[ 'blog_id' ] ) , 0 , 1 ) . ', '
                    //. 'Posts:' . count( $postsall )
                    . ' not usable:' . count( $posts )  ;

                    $i ++ ;
                    //var_dump( $posts ) ;
                    switch_to_blog( $blog[ 'blog_id' ] ) ;
                    foreach ( $posts as $post ) {
                        wp_delete_post( $post[ 'ID' ] , true ) ;
                    }
                    restore_current_blog() ;
                }
            }
            echo '<br/>' . $i ;
        }

        clean_useless_posts() ;
        ?>
    </body>
</html>
