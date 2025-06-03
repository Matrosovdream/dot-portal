<?php

namespace Tests\Feature\Traits;


trait EntityTestable {
    protected $createdRecords = [];
    protected $model;

    protected function createRecord(array $data = [])
    {
        $record = $this->model->create($data);
        if ($record) {
            $this->createdRecords[] = $record;
        }
        return $record;
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
            if( $this->model->find($record->id) ) {
                // Only delete if the record still exists
                $this->deleteRecord($record->id);
            }
        }
        $this->createdRecords = [];
    }

    protected function getRoute(string $key, array $params = []): string
    {
        if (!isset($this->routes[$key])) {
            return '';
        }
        return route($this->routes[$key], $params);
    }

    protected function storeRecordTest( string $route, array $values, $cleanUp = false ): void{

        // Perform the POST request
        $response = $this->actingAs($this->user)->
            post(
                $route,
                $values
        );

        // Verify the record exists
        $record = $this->findRecord($values);

        // Assert
        $this->assertNotNull($record, 'Record was not created successfully.');

        if ($record) {
            $this->createdRecords[] = $record;
        }

        // Clean up
        if ($cleanUp) {
            $this->deleteAllRecords();
        }

    }


}

