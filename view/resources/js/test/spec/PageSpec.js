describe("ajax通信テスト", function() {
	var details = null;

	beforeEach(function() {
		details = new Details();
	});

	afterEach(function() {
		details = null;
	});

	it("postFormの呼び出し", function() {
		spyOn($, "ajax").andCallFake(function() {
			var d = $.Deferred();
			d.resolve({
				type : 'get',
				data : postData,
				cache : false,
			});
			return d.promise();
		});
		details.getData("http://test.com", 1);
		expect(details.id).toEqual(1);
		expect(details.name).toEqual("test1");
	});

	it("getDataでデータの取得に失敗するとAlertが表示される", function() {
		spyOn($, "ajax").andCallFake(function(pos) {
			var d = $.Deferred();
			d.reject("error");
			return d.promise();
		});

		details.getData("http://test.com", 1);
	});
})