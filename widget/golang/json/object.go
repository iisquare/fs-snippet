package json

import "reflect"

type Object struct {
	data map[string]*Object // single when data is nil
	value interface{}
}

func NewObject() *Object {
	return &Object{data: make(map[string]*Object)}
}

func (this *Object) Single() bool {
	return this.data == nil
}

func (this *Object) Value() interface{} {
	return this.value
}

func (this *Object) Get(key string) *Object {
	if this.Single() {
		return NewObject()
	}
	value := this.data[key]
	if value == nil {
		return NewObject()
	}
	return value
}

func (this *Object) Put(key string, value interface{}) *Object {
	if this.Single() {
		return this
	}
	this.data[key] = Read(value)
	return this
}

func (this *Object) PutAll(data interface{}) *Object {
	if this.Single() {
		return this
	}
	Read(data).Foreach(func(key string, value *Object) bool {
		this.data[key] = value
		return true
	})
	return this
}

func (this *Object) PutKV(keys []string, values []interface{}) *Object {
	if this.Single() {
		return this
	}
	keySize := len(keys)
	valueSize := len(values)
	for i := 0; i < keySize; i++ {
		if i > valueSize {
			this.Put(keys[i], nil)
		} else {
			this.Put(keys[i], values[i])
		}
	}
	return this
}

func (this *Object) PutObject(key string) *Object {
	value := NewObject()
	if this.Single() {
		return value
	}
	this.Put(key, value)
	return value
}

func (this *Object) PutArray(key string) *Array {
	value := NewArray()
	if this.Single() {
		return value
	}
	this.Put(key, value)
	return value
}

func (this *Object) Remove(keys ...string) *Object {
	if this.Single() {
		return this
	}
	for _, key := range keys {
		delete(this.data, key)
	}
	return this
}

func (this *Object) RemoveAll() *Object {
	if this.Single() {
		return this
	}
	this.data = make(map[string]*Object)
	return this
}

func (this *Object) Has(key string) bool {
	if this.Single() {
		return false
	}
	_, ok := this.data[key]
	return ok
}

func (this *Object) Retain(keys ...string) *Object {
	if this.Single() {
		return this
	}
	result := make(map[string]*Object)
	for key, value := range this.data {
		result[key] = value
	}
	this.data = result
	return this
}

func (this *Object) Size() int {
	if this.Single() {
		return 0
	}
	return len(this.data)
}

func (this *Object) IsNil() bool {
	return this.Single() && this.value == nil
}

func (this *Object) IsBool() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Bool
}

func (this *Object) IsInt() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Int
}

func (this *Object) IsInt8() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Int8
}

func (this *Object) IsInt16() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Int16
}

func (this *Object) IsInt32() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Int32
}

func (this *Object) IsInt64() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Int64
}

func (this *Object) IsUint() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Uint
}

func (this *Object) IsUint8() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Uint8
}

func (this *Object) IsUint16() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Uint16
}

func (this *Object) IsUint32() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Uint32
}

func (this *Object) IsUint64() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Uint64
}

func (this *Object) IsUintPtr() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Uintptr
}

func (this *Object) IsFloat32() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Float32
}

func (this *Object) IsFloat64() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Float64
}

func (this *Object) IsComplex64() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Complex64
}

func (this *Object) IsComplex128() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.Complex128
}

func (this *Object) IsArray() bool {
	if !this.Single() {
		return false
	}
	if reflect.TypeOf(this.value).Kind() != reflect.Ptr {
		return false
	}
	_, ok := this.value.(*Array)
	return ok
}

func (this *Object) IsString() bool {
	return this.Single() && reflect.TypeOf(this.value).Kind() == reflect.String
}

func (this *Object) AsBool(defaultValue bool) bool {
	if !this.IsBool() || this.value == nil {
		return defaultValue
	}
	return this.value.(bool)
}

func (this *Object) AsInt(defaultValue int) int {
	if !this.IsInt() || this.value == nil {
		return defaultValue
	}
	return this.value.(int)
}

func (this *Object) AsInt8(defaultValue int8) int8 {
	if !this.IsInt8() || this.value == nil {
		return defaultValue
	}
	return this.value.(int8)
}

func (this *Object) AsInt16(defaultValue int16) int16 {
	if !this.IsInt16() || this.value == nil {
		return defaultValue
	}
	return this.value.(int16)
}

func (this *Object) AsInt32(defaultValue int32) int32 {
	if !this.IsInt32() || this.value == nil {
		return defaultValue
	}
	return this.value.(int32)
}

func (this *Object) AsInt64(defaultValue int64) int64 {
	if !this.IsInt64() || this.value == nil {
		return defaultValue
	}
	return this.value.(int64)
}

func (this *Object) AsUint(defaultValue uint) uint {
	if !this.IsUint() || this.value == nil {
		return defaultValue
	}
	return this.value.(uint)
}

func (this *Object) AsUint8(defaultValue uint8) uint8 {
	if !this.IsUint8() || this.value == nil {
		return defaultValue
	}
	return this.value.(uint8)
}

func (this *Object) AsUint16(defaultValue uint16) uint16 {
	if !this.IsUint16() || this.value == nil {
		return defaultValue
	}
	return this.value.(uint16)
}

func (this *Object) AsUint32(defaultValue uint32) uint32 {
	if !this.IsUint32() || this.value == nil {
		return defaultValue
	}
	return this.value.(uint32)
}

func (this *Object) AsUint64(defaultValue uint64) uint64 {
	if !this.IsUint64() || this.value == nil {
		return defaultValue
	}
	return this.value.(uint64)
}

func (this *Object) AsUintPtr(defaultValue uintptr) uintptr {
	if !this.IsUintPtr() || this.value == nil {
		return defaultValue
	}
	return this.value.(uintptr)
}

func (this *Object) AsFloat32(defaultValue float32) float32 {
	if !this.IsFloat32() || this.value == nil {
		return defaultValue
	}
	return this.value.(float32)
}

func (this *Object) AsFloat64(defaultValue float64) float64 {
	if !this.IsFloat64() || this.value == nil {
		return defaultValue
	}
	return this.value.(float64)
}

func (this *Object) AsComplex64(defaultValue complex64) complex64 {
	if !this.IsComplex64() || this.value == nil {
		return defaultValue
	}
	return this.value.(complex64)
}

func (this *Object) AsComplex128(defaultValue complex128) complex128 {
	if !this.IsComplex128() || this.value == nil {
		return defaultValue
	}
	return this.value.(complex128)
}

func (this *Object) AsString(defaultValue interface{}) interface{} {
	if !this.IsString() || this.value == nil {
		return defaultValue
	}
	return this.value.(string)
}

func (this *Object) AsArray() *Array {
	if !this.IsArray() || this.value == nil {
		return NewArray()
	}
	return this.value.(*Array)
}

func (this *Object) Elements() []*Object {
	if this.Single() {
		return []*Object{}
	}
	values := make([]*Object, len(this.data))
	for _, value := range this.data {
		values = append(values, value)
	}
	return values
}

func (this *Object) Names() []string {
	if this.Single() {
		return []string{}
	}
	keys := make([]string, len(this.data))
	for key := range this.data {
		keys = append(keys, key)
	}
	return keys
}

func (this *Object) Foreach(callback func (key string, value *Object) bool) {
	for key, value := range this.data {
		if !callback(key, value) {
			break
		}
	}
}
