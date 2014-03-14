<?php

class ComApplicationModelPages extends KModelTable
{
    public function __construct(KObjectConfig $config)
    {
        parent::__construct($config);

        $this->getState()
            ->insert('component', 'cmd')
            ->insert('view', 'cmd')
            ->insert('layout', 'cmd')
            ->insert('exclude', 'int');
    }

    protected function _buildQueryWhere(KDatabaseQueryInterface $query)
    {
        $state = $this->getState();

        if (!empty($state->component))
        {
            $query->where('tbl.component = :component');
            $query->bind(array('component' => $state->component));

            if (!empty($state->view))
            {
                $query->where('tbl.view = :view');
                $query->bind(array('view' => $state->view));

                if (!empty($state->layout))
                {
                    $query->where('tbl.layout = :layout');
                    $query->bind(array('layout' => $state->layout));
                }
            }
        }

        if (!empty($state->exclude))
        {
            $query->where('tbl.id NOT IN :exclude');
            $query->bind(array('exclude' => $state->exclude));
        }

        parent::_buildQueryWhere($query);
    }
}