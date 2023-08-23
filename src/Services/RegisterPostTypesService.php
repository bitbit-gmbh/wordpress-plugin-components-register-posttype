<?php # -*- coding: utf-8 -*-
namespace WDM\PluginComponents\Services;

use DI\Container;
use Laminas\Config\Factory as Config;
use Laminas\Config\Reader\Json;
use WDM\PluginComponents\Entities\PostTypeEntity;
use WDM\PluginComponents\Exceptions\PostTypeExceptions;
use WDM\PluginComponents\Factories\PostTypeFactory;
use WDM\PluginComponents\Interfaces\RegisterPostTypeInterface;
use WP_Role;

class RegisterPostTypesService implements RegisterPostTypeInterface
{
    
    private array $postTypes;
    private PostTypeEntity $postType;
    
    /**
     * @throws PostTypeExceptions
     */
    public function createPostTypes(Container $container, string $postTypeConfigPath): void
    {
        $this->postTypes = Config::fromFile($postTypeConfigPath);
        
        if (!empty($this->postTypes)) {
            $postTypeFactory = $container->get(PostTypeFactory::class);
            foreach ($this->postTypes as $postTypeProperties) {
                $this->postType = $container->call($postTypeFactory, [$container, $postTypeProperties]);
                
                if(strlen($this->postType->getSlug()) > 20){
                    throw PostTypeExceptions::postSlugToLong($this->postType->getSlug());
                }
                
                register_post_type($this->postType->getSlug(), $this->getAttributes());
            }
        }
    }
    
    public function getAttributes(): array
    {
        $defaults = [
            'labels' => $this->getLabels(),
            'description' => '',
            'public' => true,
            'exclude_from_search' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_nav_menus' => true,
            'show_in_menu' => true,
            'hierarchical' => false,
            'supports' => $this->getSupports(),
            'taxonomies' => $this->getTaxonomies(),
            'has_archive' => true,
            'rewrite' => $this->getRewrite(),
            'menu_icon' => false,
        
        ];
        
        $attributes = wp_parse_args((!empty($this->postType->getAttributes()) ? $this->postType->getAttributes() : []), $defaults);
        
        if (!empty($attributes['custom_capability'])) {
            $attributes['capability_type'] = [$this->postType->getSlug(), $this->postType->getSlug() . 's'];
            $attributes['map_meta_cap'] = true;
            $this->mapAdminCap();
            
        }
        
        $attributes = $this->enableBlockEditor($attributes);
        
        return $attributes;
    }
    
    public function mapAdminCap(): void
    {
        
        $capabilities = [
            'edit_' . $this->postType->getSlug(),
            'read_' . $this->postType->getSlug(),
            'delete_' . $this->postType->getSlug(),
            'edit_' . $this->postType->getSlug() . 's',
            'publish_' . $this->postType->getSlug() . 's',
            'read_private_' . $this->postType->getSlug() . 's',
            'delete_' . $this->postType->getSlug() . 's',
            'delete_private_' . $this->postType->getSlug() . 's',
            'delete_published_' . $this->postType->getSlug() . 's',
            'delete_others_' . $this->postType->getSlug() . 's',
            'edit_private_' . $this->postType->getSlug() . 's',
            'edit_published_' . $this->postType->getSlug() . 's',
            'edit_others_' . $this->postType->getSlug() . 's',
            'read'
        ];
        
        /** @var WP_Role $role */
        $role = get_role('administrator');
        
        foreach ($capabilities as $cap) {
            $role->add_cap($cap);
            
        }
    }
    
    public function getLabels(): array
    {
        return [
            'name' => _x($this->postType->getName(), 'Post type general name', $this->postType->getSlug()),
            'singular_name' => _x($this->postType->getSingular_name(), 'Post type singular name', $this->postType->getSlug()),
            'menu_name' => _x($this->postType->getName(), 'Post type menu name', $this->postType->getSlug()),
            'name_admin_bar' => _x($this->postType->getName(), 'Post type admin bar name', $this->postType->getSlug()),
            'all_items' => __($this->postType->getName(), $this->postType->getSlug()),
            'add_new' => _x('Add New', 'Add new post', $this->postType->getSlug()),
            'add_new_item' => __('Add New Item' . $this->postType->getSingular_name() . ' ', $this->postType->getSlug()),
            'edit_item' => __('Edit ' . $this->postType->getSingular_name(), $this->postType->getSlug()),
            'new_item' => __('New ' . $this->postType->getSingular_name(), $this->postType->getSlug()),
            'view_item' => __('View ' . $this->postType->getSingular_name(), $this->postType->getSlug()),
            'search_items' => __('Search' . $this->postType->getSingular_name(), $this->postType->getSlug()),
            'not_found' => __('No' . $this->postType->getName() . ' found.', $this->postType->getSlug()),
            'not_found_in_trash' => __('No' . $this->postType->getSingular_name() . ' found in Trash.', $this->postType->getSlug()),
            'parent_item_colon' => '',
            'parent' => __($this->postType->getSingular_name())
        ];
    }
    
    public function getSupports(): array
    {
        $defaults = [
            'title',
            'editor',
            'author',
            'thumbnail',
            'excerpt',
            'trackbacks',
            'custom-fields',
            'comments',
            'revisions',
            'page-attributes',
            'post-formats',
        ];
        
        return !empty($this->postType->getSupports()) ? $this->postType->getSupports() : $defaults;
    }
    
    /**
     * @return array
     */
    public function getTaxonomies(): array
    {
        
        $defaults = [
            'category',
            'post_tag',
        ];
        
        return !empty($this->postType->getTaxonomies()) ? $this->postType->getTaxonomies() : $defaults;
    }
    
    /**
     * @return array
     */
    public function getRewrite(): array
    {
        
        $defaults = [
            'slug' => $this->postType->getSlug(),
            'with_front' => false,
        ];
        
        $rewrite = $defaults;
        
        if (!empty($this->postType->getRewrite())) {
            $rewrite = wp_parse_args($this->postType->getRewrite(), $defaults);
        }
        
        return $rewrite;
    }
    
    /**
     * Enables the Block Editor for specific post types based on the provided attributes.
     *
     * If the 'use_block_editor' attribute is not set or is empty, this function will not enable
     * the Block Editor and will return the original attributes unchanged.
     *
     * The function will set the 'show_in_rest' attribute to true and add an action to allow the
     * Block Editor for the specified post types.
     *
     * @param array $attributes An array of attributes.
     *                          - 'use_block_editor' (bool): Set to true to enable the Block Editor for the post types.
     *
     * @return array The modified attributes with the Block Editor enabled for specified post types.
     */
    protected function enableBlockEditor(array $attributes): array
    {
        if(empty($attributes['use_block_editor'])){
            return $attributes;
        }
        
        $attributes['show_in_rest'] = true;
        
        foreach ($this->postTypes as $postType) {
            add_action(
                'use_block_editor_for_post_type',
                function ($value, $post_type) use ($postType) : bool {
                    if($postType['slug'] === $post_type){
                        $value = true;
                    }
                    
                    return $value;
                },
                10,
                2
            );
        }
        
        return $attributes;
    }
}