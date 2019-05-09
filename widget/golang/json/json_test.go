package test

import (
	"github.com/iisquare/go-spider/core/json"
	"testing"
)

func TestMap(t *testing.T) {
	data := json.NewObject()
	data.PutAll(map[string]int{"a": 1, "v": 123})
	result := json.Interface(data)
	t.Log("Interface:", result)
	str, err := json.StringifyWithError(data)
	t.Log("Stringify:", string(str), err)
}

func TestArray(t *testing.T) {
	data := json.NewObject()
	data.Put("c", []string{"a", "c", "s"})
	data.Put("d", []interface{}{1, "c", map[string]interface{}{"a": 1, "b": "sss", "c": []int{1, 3, 5}}})
	result := json.Interface(data)
	t.Log("Interface:", result)
	str, err := json.StringifyWithError(data)
	t.Log("Stringify:", string(str), err)
}

func TestJson(t *testing.T) {
	data := json.NewObject()
	data.Put("a", 1)
	data.Put("b", 2)
	data.Put("c", 3)
	data.Put("d", "xxxx")
	data.Put("f", "xxxxxxx")
	info := data.PutObject("info")
	info.PutAll(struct {
		Id int
		Name string
		Adr string `json:"address"`
		p string
	}{Id: 1, Name: "xsssss", Adr: "fdfdf"})
	children := data.PutArray("children")
	children.Add("a", "c", "f")
	result := json.Interface(data)
	t.Log("Interface:", result)
	str, err := json.StringifyWithError(data)
	t.Log("Stringify:", string(str), err)
	t.Log("ChildrenSize:", children.Size())
	result = json.Interface(children)
	t.Log("ChildrenInterface:", result)
	str, err = json.StringifyWithError(children)
	t.Log("ChildrenStringify:", string(str), err)
}
