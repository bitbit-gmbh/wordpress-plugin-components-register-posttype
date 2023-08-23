<?php # -*- coding: utf-8 -*-

namespace WDM\PluginComponents\Interfaces;

interface RegisterPostTypeInterface
{
    /**
     * @return array
     */
    public function getLabels(): array;

    /**
     * @return array
     */
    public function getSupports(): array;

    /**
     * @return array
     */
    public function getTaxonomies(): array;

    /**
     * @return array
     */
    public function getRewrite(): array;

    /**
     * @return array
     */
    public function getAttributes(): array;
}