<?php # -*- coding: utf-8 -*-
namespace WDM\PluginComponents\Entities;

use WDM\PluginComponents\Interfaces\PostTypeEntityInterface;

class PostTypeEntity implements PostTypeEntityInterface
{
    protected string $name = '';
    protected string $singular_name = '';
    protected string $slug = '';

    protected array $supports = [];
    protected array $attributes = [];
    protected array $rewrite = [];
    protected array $taxonomies = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSingular_name(): string
    {
        return $this->singular_name;
    }

    /**
     * @param string $singular_name
     */
    public function setSingular_name(string $singular_name): void
    {
        $this->singular_name = $singular_name;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return bool
     */
    public function isHierarchical(): bool
    {
        return $this->hierarchical;
    }

    /**
     * @param bool $hierarchical
     */
    public function setHierarchical(bool $hierarchical): void
    {
        $this->hierarchical = $hierarchical;
    }

    /**
     * @return bool
     */
    public function isCustomCapability(): bool
    {
        return $this->custom_capability;
    }

    /**
     * @param bool $custom_capability
     */
    public function setCustomCapability(bool $custom_capability): void
    {
        $this->custom_capability = $custom_capability;
    }

    /**
     * @return array
     */
    public function getSupports(): array
    {
        return $this->supports;
    }

    /**
     * @param array $supports
     */
    public function setSupports(array $supports): void
    {
        $this->supports = $supports;
    }

    /**
     * @return array
     */
    public function getTaxonomies(): array
    {
        return $this->taxonomies;
    }

    /**
     * @param array $taxonomies
     */
    public function setTaxonomies(array $taxonomies): void
    {
        $this->taxonomies = $taxonomies;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getRewrite(): array
    {
        return $this->rewrite;
    }

    /**
     * @param array $rewrite
     */
    public function setRewrite(array $rewrite): void
    {
        $this->rewrite = $rewrite;
    }
}
