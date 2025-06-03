<?php

namespace Tests\Feature\Abstracts;


trait EntityAbstract {

    protected $createdRecords = [];
    protected $model;

    protected function createRecord(array $data = [])
    {
        return $this->model->create($data);
    }

    protected function updateRecord( $record_id, array $data = [] )
    {
        $record = $this->model->find($record_id);
        $record->update($data);
        return $record->refresh();
    }

    protected function deleteRecord( $record_id ): void
    {
        $record = $this->model->find($record_id);
        $record->delete();
    }

    protected function findRecord(array $filter)
    {
        return $this->model->where($filter)->first();
    }

    protected function deleteAllRecords(): void
    {
        foreach ($this->createdRecords as $record) {
            $this->deleteRecord($record->id);
        }
        $this->createdRecords = [];
    }

}

