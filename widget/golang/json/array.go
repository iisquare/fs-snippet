package json

import "github.com/iisquare/go-spider/core/util"

type Array struct {
	data []*Object
}

func NewArray() *Array {
	return &Array{data: make([]*Object, 0)}
}

func (this *Array) Get(index int) *Object {
	if index >= len(this.data) {
		return NewObject()
	}
	return this.data[index]
}

func (this *Array) Add(items ...interface{}) *Array {
	for _, item := range items {
		this.data = append(this.data, Read(item))
	}
	return this
}

func (this *Array) AddObject() *Object {
	item := NewObject()
	this.data = append(this.data, item)
	return item
}

func (this *Array) AddArray() *Array {
	return this.AddObject().AsArray()
}

func (this *Array) Set(index int, item interface{}) *Array {
	if index < len(this.data) {
		this.data[index] = Read(item)
	}
	return this
}

func (this *Array) Insert(index int, items ...interface{}) *Array {
	if index < len(this.data) {
		data := make([]*Object, len(this.data) + len(items))
		if index > 0 {
			data = append(data, this.data[0:index - 1]...)
		}
		for _, item := range items {
			data = append(data, Read(item))
		}
		data = append(data, this.data[index:]...)
		this.data = data
	} else {
		this.Add(items...)
	}
	return this
}

func (this *Array) Remove(indexes ...int) *Array {
	data := make([]*Object, len(this.data) - len(indexes))
	for index, item := range this.data {
		if !util.Array.Contains(util.Number.InterfaceInt(indexes...), index) {
			data = append(data, item)
		}
	}
	this.data = data
	return this
}

func (this *Array) RemoveAll() *Array {
	this.data = []*Object{}
	return this
}

func (this *Array) Retain(indexes ...int) *Array {
	data := make([]*Object, len(this.data) - len(indexes))
	for index, item := range this.data {
		if util.Array.Contains(util.Number.InterfaceInt(indexes...), index) {
			data = append(data, item)
		}
	}
	this.data = data
	return this
}

func (this *Array) Has(index int) bool {
	return index < len(this.data)
}

func (this *Array) Exist(item interface{}) bool {
	for _, v := range this.data {
		if item == v.Value() {
			return true
		}
	}
	return false
}

func (this *Array) Size() int {
	return len(this.data)
}

func (this *Array) Elements() []*Object {
	return this.data
}

func (this *Array) Foreach(callback func (index int, item *Object) bool) {
	for index, item := range this.data {
		if !callback(index, item) {
			break
		}
	}
}
