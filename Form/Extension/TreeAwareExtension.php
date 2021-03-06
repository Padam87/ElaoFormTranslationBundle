<?php

/**
 * This file is part of the ElaoFormTranslation bundle.
 *
 * Copyright (C) 2014 Elao
 *
 * @author Elao <contact@elao.com>
 */

namespace Elao\Bundle\FormTranslationBundle\Form\Extension;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractTypeExtension;
use Elao\Bundle\FormTranslationBundle\Builders\FormTreebuilder;
use Elao\Bundle\FormTranslationBundle\Builders\FormKeybuilder;

/**
 * Tree Aware Extension
 */
abstract class TreeAwareExtension extends AbstractTypeExtension
{
    /**
     * Form Tree treeBuilder
     *
     * @var FormTreebuilder
     */
    protected $treeBuilder;

    /**
     * Form Key treeBuilder
     *
     * @var FormKeybuilder
     */
    protected $keyBuilder;

    /**
     * Buildable keys list
     *
     * @var array
     */
    protected $keys;

    /**
     * Whether automatic generation of missing keys is enabled or not
     *
     * @var boolean
     */
    protected $autoGenerate = false;

    /**
     * Enable or disable automatic generation of missing labels
     *
     * @param boolean $enabled The Boolean
     */
    public function setAutoGenerate($enabled)
    {
        $this->autoGenerate = $enabled;
    }

    /**
     * Set Tree Builder
     *
     * @param FormTreebuilder $treeBuilder The FormKeyBuilder
     */
    public function setTreebuilder(FormTreebuilder $treeBuilder = null)
    {
        $this->treeBuilder = $treeBuilder;
    }

    /**
     * Set Key Builder
     *
     * @param FormKeybuilder $keyBuilder The FormKeyBuilder
     */
    public function setKeybuilder(FormKeybuilder $keyBuilder = null)
    {
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * Set buildable keys
     *
     * @param array $keys Array of keys
     */
    public function setKeys(array $keys)
    {
        $this->keys = $keys;
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($this->treeBuilder && $this->keyBuilder) {
            foreach ($this->keys as $key => $value) {
                if (isset($options[$key]) && $options[$key] === true) {
                    $this->generateKey($view, $key, $value);
                }
            }
        }
    }

    /**
     * Generate the key for the given view field
     *
     * @param FormView $view The form view
     * @param string $key a key
     * @param string $value
     */
    protected function generateKey(FormView &$view, $key, $value)
    {
        if (!isset($view->vars['tree'])) {
            $view->vars['tree'] = $this->treeBuilder->getTree($view);
        }

        $view->vars[$key] = $this->keyBuilder->buildKeyFromTree($view->vars['tree'], $value);
    }
}
