<?php
/**
 * @author AlexBrin https://vk.com/alex__brin
 */

namespace AlexBrin\Qiwi\Form\Base;


use pocketmine\Player;

class CustomForm extends Form
{
    protected $content = [];
    protected $labelMap = [];
    protected $result = [];

    public function getType(): string
    {
        return self::TYPE_CUSTOM;
    }

    public function onSubmit(Player $player): void
    {
    }

    public function onClose(Player $player): void
    {
    }

    public function addLabel(string $text, ?string $label = null)
    {
        $this->addContent(['type' => 'label', 'text' => $text], $label);
    }

    public function addToggle(string $text, bool $default = null, ?string $label = null)
    {
        $content = ['type' => 'toggle', 'text' => $text];
        if ($default !== null)
            $content['default'] = $default;
        $this->addContent($content, $label);
    }

    public function addSlider(string $text, int $min, int $max, int $step = -1, int $default = -1, ?string $label = null)
    {
        $content = ['type' => 'slider', 'text' => $text, 'min' => $min, 'max' => $max];

        if ($step > -1)
            $content['step'] = $step;

        if ($default > -1)
            $content['default'] = $default;

        $this->addContent($content, $label);
    }

    public function addStepSlider(string $text, array $steps, int $defaultIndex = -1, ?string $label = null)
    {
        $content = ['type' => 'step_slider', 'text' => $text, 'steps' => $steps];
        if ($defaultIndex > -1)
            $content['default'] = $defaultIndex;

        $this->addContent($content, $label);
    }

    public function addDropdown(string $text, array $options, int $default = null, ?string $label = null)
    {
        $this->addContent(['type' => 'dropdown', 'text' => $text, 'options' => $options, 'default' => $default], $label);
    }

    public function addInput(string $text, string $placeholder = '', string $default = null, ?string $label = null)
    {
        $this->addContent(['type' => 'input', 'text' => $text, 'placeholder' => $placeholder, 'default' => $default], $label);
    }

    public function addContent(array $content, ?string $label = null)
    {
        $this->content[] = $content;
        $this->labelMap[] = $label ?? count($this->labelMap);
    }

    public function handleResponse(Player $player, $data): void
    {
        if ($data === null) {
            $this->onClose($player);
            return;
        }

        $new = [];
        foreach ($data as $i => $v)
            $new[$this->labelMap[$i]] = $v;
        $this->result = $new;
        $this->onSubmit($player);
    }

    public function serializeFormData(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}