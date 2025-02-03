<?php
namespace App\Repositories\FormFieldValue;

use App\Models\CartFieldValue;
use App\Models\OrderFieldValue;
use App\Models\TravellerFieldValue;
use App\Repositories\FormFieldReference\FormFieldReferenceRepo;

class FormFieldValueRepo
{

    protected $fieldRef;
    protected $cartModel;
    protected $orderModel;
    protected $travellerModel;

    protected $fields = [
        'cart_id',
        'field_id',
        'value',
    ];

    public function __construct()
    {
        $this->fieldRef = new FormFieldReferenceRepo();
        $this->cartModel = new CartFieldValue();
        $this->orderModel = new OrderFieldValue();
        $this->travellerModel = new TravellerFieldValue();
    }

    public function getCartValues($cart_id)
    {

        $values = $this->cartModel->where('cart_id', $cart_id)->get();
        foreach ($values as $key => $value) {
            $data[$value->field_id] = $this->prepareField($value);
        }
        return $data ?? [];

    }

    public function setCartValue($cart_id, $field_id, $value)
    {
        $data = $this->cartModel->where('cart_id', $cart_id)->where('field_id', $field_id)->first();
        if ($data) {
            $data->value = $value ?? '';
            $data->save();
        } else {
            $data = new CartFieldValue();
            $data->cart_id = $cart_id;
            $data->field_id = $field_id;
            $data->value = $value ?? '';
            $data->save();
        }
    }

    public function getCartValueBy($cart_id, $entity, $field_by)
    {

        $field = $this->getFieldBy($entity, $field_by);
        
        if (isset($field)) {
            $field_id = $field['id'];

            $data = $this->cartModel
                ->where('cart_id', $cart_id)
                ->where('field_id', $field_id)->first();
            return $data->value ?? '';
        }

        return null;

    }

    public function getFieldBy($entity, $field_by)
    {
        // Get from reference where 'field_by' = 1
        $filter = [
            'entity' => $entity
        ];
        if( $field_by == 'email' ) { $filter['is_email'] = 1; }
        if( $field_by == 'phone' ) { $filter['is_phone'] = 1; }
        if( $field_by == 'fullname' ) { $filter['is_fullname'] = 1; }
        if( $field_by == 'name' ) { $filter['is_name'] = 1; }
        if( $field_by == 'lastname' ) { $filter['is_lastname'] = 1; }
        if( $field_by == 'birthday' ) { $filter['is_birthday'] = 1; }
        if( $field_by == 'passport' ) { $filter['is_passport'] = 1; }
        $fields = $this->fieldRef->getInitialFields($filter);

        if (isset($fields[0])) {
            return $fields[0];
        }

        return null;
    }

    public function getCartValue($cart_id, $field_id)
    {
        $data = $this->cartModel->where('cart_id', $cart_id)->where('field_id', $field_id)->first();
        return $data;
    }

    public function getOrderValues($order_id)
    {

        $values = $this->orderModel->where('order_id', $order_id)->get();
        foreach ($values as $key => $value) {
            $data[$value->field_id] = $this->prepareField($value);
        }
        return $data ?? [];

    }

    public function setOrderValue($order_id, $field_id, $value)
    {
        $data = $this->orderModel->where('order_id', $order_id)->where('field_id', $field_id)->first();
        if ($data) {
            $data->value = $value ?? '';
            $data->save();
        } else {
            $data = new OrderFieldValue();
            $data->order_id = $order_id;
            $data->field_id = $field_id;
            $data->value = $value ?? '';
            $data->save();
        }
    }

    public function getOrderValue($order_id, $field_id)
    {
        $data = $this->orderModel->where('order_id', $order_id)->where('field_id', $field_id)->first();
        return $data;
    }

    public function getTravellerValues($traveller_id)
    {
        $values = $this->travellerModel->where('traveller_id', $traveller_id)->get();
        foreach ($values as $key => $value) {
            $data[$value->field_id] = $this->prepareField($value);
        }
        return $data ?? [];
    }

    public function setTravellerValue($traveller_id, $field_id, $value)
    {
        $data = $this->travellerModel->where('traveller_id', $traveller_id)->where('field_id', $field_id)->first();
        if ($data) {
            $data->value = $value ?? '';
            $data->save();
        } else {
            $data = new TravellerFieldValue();
            $data->traveller_id = $traveller_id;
            $data->field_id = $field_id;
            $data->value = $value ?? '';
            $data->save();
        }
    }

    public function getTravellerValue($traveller_id, $field_id)
    {
        $data = $this->travellerModel->where('traveller_id', $traveller_id)->where('field_id', $field_id)->first();
        return $data;
    }

    public function prepareField($field)
    {
        $data = [
            'id' => $field->id,
            'cart_id' => $field->cart_id,
            'field_id' => $field->field_id,
            'value' => $field->value,
        ];
        return $data;
    }


}