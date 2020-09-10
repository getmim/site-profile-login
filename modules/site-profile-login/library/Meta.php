<?php
/**
 * Meta
 * @package site-profile-login
 * @version 0.0.1
 */

namespace SiteProfileLogin\Library;


class Meta
{
	static function single(object $page){
        $result = [
            'head' => [],
            'foot' => []
        ];

        $home_url = \Mim::$app->router->to('siteHome');

        $page = (object)[
            'title'         => $page->title,
            'description'   => $page->description,
            'schema'        => 'WebSite',
            'keyword'       => '',
            'page'          => \Mim::$app->router->to('siteProfileLogin')
        ];

        $result['head'] = [
            'description'       => $page->description,
            'schema.org'        => [],
            'type'              => 'article',
            'title'             => $page->title,
            'url'               => $page->page,
            'metas'             => []
        ];

        // schema breadcrumbList
        $result['head']['schema.org'][] = [
            '@context'  => 'http://schema.org',
            '@type'     => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'item' => [
                        '@id' => $home_url,
                        'name' => \Mim::$app->config->name
                    ]
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'item' => [
                        '@id' => $home_url . '#auth',
                        'name' => 'Auth'
                    ]
                ]
            ]
        ];

        return $result;
    }
}