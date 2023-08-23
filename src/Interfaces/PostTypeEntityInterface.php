<?php # -*- coding: utf-8 -*-

namespace WDM\PluginComponents\Interfaces;

interface PostTypeEntityInterface
{
    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $name
     */
    public function setName(string $name): void;

    /**
     * @return string
     */
    public function getSingular_name(): string;

    /**
     * @param string $singular_name
     */
    public function setSingular_name(string $singular_name): void;

    /**
     * @return string
     */
    public function getSlug(): string;

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void;

    /**
     * @return bool
     */
    public function isHierarchical(): bool;

    /**
     * @param bool $hierarchical
     */
    public function setHierarchical(bool $hierarchical): void;

    /**
     * @return bool
     */
    public function isCustomCapability(): bool;

    /**
     * @param bool $custom_capability
     */
    public function setCustomCapability(bool $custom_capability): void;

    /**
     * @return array
     */
    public function getSupports(): array;

    /**
     * @param array $supports
     */
    public function setSupports(array $supports): void;

    /**
     * @return array
     */
    public function getTaxonomies(): array;

    /**
     * @param array $taxonomies
     */
    public function setTaxonomies(array $taxonomies): void;

    /**
     * @return array
     */
    public function getAttributes(): array;

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes): void;

    /**
     * @return array
     */
    public function getRewrite(): array;

    /**
     * @param array $rewrite
     */
    public function setRewrite(array $rewrite): void;
}