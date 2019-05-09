package json

import (
	"encoding/json"
	"github.com/iisquare/go-spider/core/util"
	"reflect"
	"unicode"
)

func Parse(data []byte) *Object {
	result, err := ParseWithError(data)
	if err != nil {
		return nil
	}
	return result
}

func Stringify(v interface{}) []byte {
	result, err := StringifyWithError(v)
	if err != nil {
		return nil
	}
	return result
}

func ParseWithError(data []byte) (*Object, error) {
	var result interface{}
	err := json.Unmarshal(data, &result)
	if err != nil {
		return NewObject(), err
	}
	return Read(result), nil
}

func StringifyWithError(v interface{}) ([]byte, error) {
	return json.Marshal(Interface(v))
}

func Read(data interface{}) *Object {
	result := NewObject()
	dataType := reflect.TypeOf(data)
	switch dataType.Kind() {
	case reflect.Ptr:
		//fmt.Println("Ptr:", data, result)
		if _, ok := data.(*Object); ok {
			result = data.(*Object)
		} else if _, ok := data.(*Array); ok {
			result.data = nil
			result.value = data
		} else {
			result = Read(reflect.ValueOf(data).Elem().Interface())
		}
	case reflect.Slice, reflect.Array:
		dataValue := reflect.ValueOf(data)
		count := dataValue.Len()
		arr := NewArray()
		for i := 0; i < count; i++ {
			arr.Add(dataValue.Index(i).Interface())
		}
		result.data = nil
		result.value = arr
	case reflect.Map:
		dataValue := reflect.ValueOf(data)
		for _, key := range dataValue.MapKeys() {
			result.Put(key.String(), dataValue.MapIndex(key).Interface())
		}
	case reflect.Struct:
		dataValue := reflect.ValueOf(data)
		count := dataType.NumField()
		for i := 0; i < count; i++ {
			field := dataType.Field(i)
			name := field.Name
			if unicode.IsLower(rune(name[0])) {
				continue
			}
			if tag, ok := field.Tag.Lookup("json"); ok {
				name = tag
			}
			result.Put(util.Strings.LcFirst(name), dataValue.Field(i).Interface())
		}
	default:
		result.data = nil
		result.value = data
	}
	return result
}

func Interface(data interface{}) interface{} {
	dataType := reflect.TypeOf(data)
	if dataType.Kind() != reflect.Ptr {
		return data
	}
	dataValue := reflect.ValueOf(data)
	if dataValue.IsNil() {
		return data
	}
	if obj, ok := data.(*Object); ok {
		if obj.Single() {
			return Interface(obj.Value())
		}
		result := make(map[string]interface{})
		obj.Foreach(func(key string, value *Object) bool {
			result[key] = Interface(value)
			return true
		})
		return result
	}
	if arr, ok := data.(*Array); ok {
		result := make([]interface{}, 0)
		arr.Foreach(func(index int, item *Object) bool {
			result = append(result, Interface(item))
			return true
		})
		return result
	}
	return Interface(dataValue.Elem())
}

func Write(data *Object, v interface{}) error {
	result, err := StringifyWithError(data)
	if err != nil {
		return err
	}
	return json.Unmarshal(result, v)
}
