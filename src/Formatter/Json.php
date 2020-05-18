<?php
namespace BulkExport\Formatter;

class Json extends AbstractFormatter
{
    protected $label = 'json';
    protected $extension = 'json';
    protected $headers = [
        'Content-type' => 'application/json; charset=utf-8',
    ];
    protected $defaultOptions = [
        'flags' => JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_LINE_TERMINATORS | JSON_PARTIAL_OUTPUT_ON_ERROR,
    ];

    protected $api;

    protected function process()
    {
        $this->api = $this->services->get('ControllerPluginManager')->get('api');

        if ($this->isSingle) {
            return $this->processSingle();
        }

        if ($this->isId) {
            $list = [];
            // TODO Use the entityManager and expresion in()?
            foreach( $this->resources as $resourceId) {
                try {
                    $list[] = $this->api->read($this->resourceType, ['id' => $resourceId])->getContent();
                } catch (\Omeka\Api\Exception\NotFoundException $e) {
                }
            }
        } elseif ($this->isQuery) {
            $list = $this->api->search($this->resourceType, $this->query)->getContent();
        } else {
            $list = &$this->resources;
        }

        $this->content = json_encode($list, $this->options['flags']);
    }

    protected function processSingle()
    {
        $resource = reset($this->resources);
        if ($this->isId) {
            try {
                $resource = $this->api->read($this->resourceType, ['id' => $resource])->getContent();
            } catch (\Omeka\Api\Exception\NotFoundException $e) {
                $resource = null;
            }
        }
        $this->content = json_encode($resource, $this->options['flags']);
    }
}