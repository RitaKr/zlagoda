$(document).ready(function () {
	//handles delete of item from database on delete button click. it sends request to actions.php where the sql query is executed, gets a response back and alerts the status of the action
	//note that in order for sql query to be executed each button must have data attributes with name of the table (data-table), name of PK (data-key), and id of item to be deleted (data-id)
	$(".content-table .delete, .bill .delete").click(function (e) {
		e.preventDefault();
		//getting the data for the query from button data-attributes
		const id = $(this).attr("meta-id");
		const table = $(this).attr("meta-table");
		const key = $(this).attr("meta-key");

		//confirmation dialog before deleting
		if (
			confirm(
				"Are you sure you want to delete this " + table.toLowerCase() + "?"
			)
		) {
			//sending request to actions.php with action delete
			$.ajax({
				url: "../../actions.php?action=delete",
				type: "POST",
				data: { table: table, id: id, key: key },
				//reload the page after the action is done
				success: function () {
					location.reload();
				},
			});
		}
	});

	//hides banner on its close button (x) click
	$(".banner .close").click(function (e) {
		$(this).parent().hide();
	});

	//validates forms: disables submit button until each required field has value (white spaces are not a value)
	$("form input[required], form select[required], form textarea[required]").on(
		"keyup change",
		function () {
			let empty = false;
			const form = $(this).closest("form");
			//console.log("input changed");
			form
				.find("input[required], select[required], textarea[required]")
				.each(function () {
					//console.log($(this).val().trim())
					if ($(this).val().trim() == "") {
						empty = true;
					}
				});

			if (empty) {
				form.find('button[type="submit"]').attr("disabled", "disabled");
			} else {
				form.find('button[type="submit"]').removeAttr("disabled");
			}
		}
	);

	//submits form with filters when filter value is changed
	$(".filters-form select, .totals-form select").on("change", function () {
		$(this).closest("form").submit();
	});
	$('.filters-form input[type="date"]').on("blur", function () {
		$(this).closest("form").submit();
	});
	$(".filters-form input").on("input", function () {
		if ($(this).val() === "") {
			$(this).closest("form").submit();
		}
	});
	$("#edit-form .edit, #edit-form .delete").click(function (e) {
		e.preventDefault();
	});

	$(".edit").click(function (e) {
		e.preventDefault();

		const row = $(this).closest("tr");
		row.append(
			`<input type="hidden" name="id" value="${$(this).attr("meta-id")}">`
		);
		row.append(
			`<input type="hidden" name="key" value="${$(this).attr("meta-key")}">`
		);
		row.append(
			`<input type="hidden" name="table" value="${$(this).attr("meta-table")}">`
		);
		const deleteBtn = row.find(".delete");
		row.toggleClass("editing", true);
		const cells = row.find("td[data-key]");
		//console.log(cells);
		//cells.splice(-2);
		const editButton = $(this);
		const cancelBtn =
			$(`<button class="table-btn cancel" title="Withdraw changes">
            <svg xmlns="http://www.w3.org/2000/svg"  fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
            <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/>
          </svg></button>`);

		const editButtonInner = editButton.html();
		const originalText = [];

		if (editButton.attr("aria-roledescription") == "edit") {
			// Change the text to an input field
			cells.each(function () {
				const keyName = this.dataset["key"];
				const fk = this.dataset["fk"];
				const fkVal = this.dataset["val"];
				const nn = this.dataset["nn"];
				const options = this.dataset["options"];
				const type = this.dataset["type"];
				const max = this.dataset["max"];
				const min = this.dataset["min"];
				const maxlength = this.dataset["maxlength"];
				const text = this.innerText;
				originalText.push(text); //preserving original values for the case of canceling

				const td = $(this);
				const infoCells = td.siblings("td[data-info]");
				//in case of FK we use select
				if (fk) {
					//console.log(fk);
					const fkArray = JSON.parse(fk);
					let infoValues = fkArray.reduce((acc, pr) => {
						const { id, item_name, ...info } = pr;
						acc[id] = Object.values(info);
						return acc;
					}, {});
					console.log("infoValues:", infoValues);
					td.html(
						`<select name="${keyName}">
                        ${fkArray
													.map((fk) => {
														const { id, item_name, ...info } = fk;
														//console.log(info);

														return `<option value="${id}" ${
															id == fkVal ? "selected" : ""
														}>${item_name}</option>`;
													})
													.join("")}</select>`
					);
					const select = td.children("select");
					select.on("change", function () {
						const selected = $(this).find("option:selected");
						const id = selected.val();

						infoValues[id].forEach((info, i) => {
							infoCells[i].innerText = info;
						});
					});
					//console.log(infoValues);
				} else if (options) {
					let optionsObject = JSON.parse(options);
					console.log(optionsObject);
					if (Array.isArray(optionsObject)) {
						console.log("is array");
						optionsObject = optionsObject.map((option, i) => {
							return { [i]: option };
						});
					}
					console.log(optionsObject);
					td.html(
						`<select name="${keyName}">
                        ${Object.entries(optionsObject)
													.map(([key, val]) => {
														return `<option value="${key}" ${
															text == val ? "selected" : ""
														}>${val}</option>`;
													})
													.join("")}
                        </select>`
					);
				} else {
					//otherwise text input
					td.html(
						`<input type="${
							type == "date" ? "date" : "text"
						}" name="${keyName}" value="${text}" ${nn ? "required " : ""}
							 maxlength="${maxlength ? maxlength : 50}"
                             ${max ? "max=" + max : ""}>`
					);

					const input = td.children("input");
					input.on("keyup change input", function () {
						if (this.required && input.val().trim() == "") {
							$(this).toggleClass("invalid", true);
						} else {
							$(this).toggleClass("invalid", false);
						}
						if (type == "double") {
							console.log(
								input.val(),
								parseFloat(input.val()),
								input.val() == parseFloat(input.val())
							);
							if (
								isNaN(parseFloat(input.val())) ||
								parseFloat(input.val()) < (min ? min : 0) ||
								parseFloat(input.val()) > max ||
								input.val() != parseFloat(input.val())
							) {
								$(this).toggleClass("invalid", true);
							} else {
								$(this).toggleClass("invalid", false);
							}
						}
						if (type == "int") {
							console.log(
								input.val(),
								parseInt(input.val()),
								input.val() == parseInt(input.val())
							);
							if (
								isNaN(parseInt(input.val())) ||
								parseInt(input.val()) < (min ? min : 0) ||
								parseInt(input.val()) > max ||
								input.val() != parseInt(input.val())
							) {
								$(this).toggleClass("invalid", true);
							} else {
								$(this).toggleClass("invalid", false);
							}
						}

						//console.log(parseFloat(input.val()));
						//console.log("empty:", empty);

						editButton.prop("disabled", row.has("td .invalid").length > 0);
					});
				}
			});

			// Change the edit button to a submit button
			editButton.html(`<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
          </svg>`);
			editButton.attr("aria-roledescription", "submit-edit");
			editButton.attr("title", "Apply changes");

			//handle canceling
			cancelBtn.click(function (e) {
				e.preventDefault();

				if (confirm("Are you sure you want to withdraw changes?")) {
					location.reload();
				}
			});
			deleteBtn.replaceWith(cancelBtn);
			console.log(deleteBtn);
		} else {
			if (confirm("Are you sure you want to apply this changes?")) {
				var form = row.parents().find("form#edit-form")[0];
				form.submit();
			}
		}
	});

	$(".print").click(function () {
		var clone = $(".content-table").clone(); // clone the table
		clone.find("td:has(button)").remove(); // remove td's that contain a button
        clone.find("th.empty").remove(); 
		var printContents = $("<div>").append(clone).html(); // get the outer html of the cloned table
		var originalContents = document.body.innerHTML;

		document.body.innerHTML =
			`<style type="text/css" media="print">
            
        @media print {
            .content-table thead {
                top: 0;
            }
            @page {
                
                margin-bottom: 0;
            }
            body {
                
                padding-bottom: 1cm ;
            }
        }
  </style>` + printContents;

		window.print();
		document.body.innerHTML = originalContents;
		location.reload();
	});

	$(".print-bills").click(function () {
		var clone = $(".bills-container").clone(); // clone the table
		clone.find(".bill button").remove(); // remove td's that contain a button
		var printContents = $("<div>").append(clone).html(); // get the outer html of the cloned table
		var originalContents = document.body.innerHTML;

		document.body.innerHTML =
			`<style type="text/css" media="print">
        @media print {
            
            @page {
                
                margin-bottom: 0;
            }
            body {
                
                padding-bottom: 1cm ;
            }
        }
  </style>` + printContents;

		window.print();
		document.body.innerHTML = originalContents;
		location.reload();
	});

	handleBillForm();

	function handleBillForm() {
		const billForm = $("#create-bill-form");
		var allProducts = []; // Array of all UPCs
		var selectedUPCs = []; // Array of selected UPCs
        let percent = 0;
		// Function to update the options of a select
		function updateOptions(select) {
			var selectedUPC = select.val();
			//console.log("selectedUPCs in updateOptions: " + selectedUPCs);
			//console.log("selectedUPC in updateOptions: " + selectedUPC);
			select.empty();
			select.append(new Option("Select product", ""));
			$.each(allProducts, function (index, product) {
				if (
					selectedUPCs.indexOf(product.upc) === -1 ||
					product.upc === selectedUPC
				) {
					select.append(new Option(product.info, product.upc));
				}
			});
			//console.log(select);
			select.val(selectedUPC);
		}

		function updateDeleteButtons() {
            updateTotals();
			billForm.find(".bill-item-fieldset .delete").click(function (e) {
				e.preventDefault();
				e.stopPropagation();
				console.log("clicked on ", $(this));
				const fieldset = $(this).parents(".bill-item-fieldset");
				if (!$(this).attr("disabled")) {
					const upc = fieldset.find(".UPC").val();
                    
					if (selectedUPCs.includes(upc)) {
						selectedUPCs.splice(selectedUPCs.indexOf(upc), 1);
					}
                    console.log("selectedUPCs after delete:", selectedUPCs)
					fieldset.remove();
					updateDeleteButtons();
				}
			});
			if (billForm.find(".bill-item-fieldset").length < 2) {
				billForm
					.find(".bill-item-fieldset .delete")
					.attr("disabled", "disabled");
			} else {
				billForm.find(".bill-item-fieldset .delete").removeAttr("disabled");
			}
		}

        function updateTotals(){
            let totals = billForm.find(".bill-item-fieldset").toArray().reduce(function(acc, item){
                const quantity = parseInt($(item).find(".product_number").val());
                const price = parseFloat($(item).find(".selling_price-output").text());
                //console.log($(item).find(".selling_price"), $(item).find(".selling_price").text());
                const total = quantity * price;
                acc += total;
                //console.log(quantity, price);
                return acc;
            }, 0);
            let discount = totals*(parseFloat(percent)*0.01);
            totals -= discount;
            billForm.find("#sum_total-output").text(totals.toFixed(2));
            billForm.find("#vat-output").text((totals*0.2).toFixed(2));
            billForm.find("#sum_total").val(totals.toFixed(2));
            billForm.find("#vat").val((totals*0.2).toFixed(2));
            billForm.find("#discount").val(discount.toFixed(2));
        }
		function updateOutputs() {
			updateDeleteButtons();
			updateSubmitBtn();
            updateTotals();
            billForm.find("#card_number").change(getDiscountPercent);
			billForm.find(".bill-item-fieldset").each(function (i) {
				//console.log("select #" + i);
				const fieldset = $(this);
				const select = fieldset.find(".UPC");
				//console.log(select);
				//if(!select.attr("disabled"))
				updateOptions(select);
				var oldUPC = select.val();
				//console.log("oldUPC: " + oldUPC);

				if (oldUPC && !selectedUPCs.includes(oldUPC)) {
					selectedUPCs.push(oldUPC);
				}
				//console.log("selectedUPCs before change: " + selectedUPCs);
				select.change(function (e) {
					e.stopImmediatePropagation();
					//console.log("select #" + i);
					//console.log("oldUPC in change: " + oldUPC);
					//console.log("oldUPC: " + oldUPC);
					var newUPC = select.val();
					//console.log("newUPC: " + newUPC);
					if (newUPC && !selectedUPCs.includes(newUPC)) {
						selectedUPCs.push(newUPC);
					}
					if (oldUPC) {
						selectedUPCs.splice(selectedUPCs.indexOf(oldUPC), 1);
					}

					console.log("selectedUPCs after change: " + selectedUPCs);
					billForm.find(".UPC").each(function () {
						//console.log(!$(this).is(select));
						updateOptions($(this));
					});

					getProductInfo(fieldset);
					oldUPC = select.val();
					updateSubmitBtn();
                    
				});
                fieldset.find(".product_number").change(function(){
                    updateTotals();
                
                })
				getProductInfo(fieldset);
                
			});
		}

		// Populate allUPCs array and update outputs
		$.get("actions.php?action=get_all_products", function (response) {
			allProducts = JSON.parse(response);
			//console.log(allProducts)
			allProducts = allProducts.map(function (product) {
				return {
					upc: product.UPC,
					info:
						product.product_name +
						" by " +
						product.producer +
						(product.promotional_product == 1 ? " (promotional)" : ""),
				};
			});
			console.log(allProducts);
			updateOutputs();
		});

		const itemFieldset = billForm.find(".bill-item-fieldset").first().clone(); // Clone the first fieldset

		// Add a click event to the add button
		$("#add-item-button").click(function (e) {
			e.preventDefault();
			if (allProducts.length === selectedUPCs.length) {
				alert("All products have been selected");
				return;
			}
			// Clone the fieldset and append it to the form
			const newFieldset = itemFieldset.clone();
			// billForm.find(".bill-item-fieldset").each(function () {
			//     $(this).find(".UPC").attr("disabled", "disabled");
			// })

			billForm.find(".items-container").append(newFieldset);
			updateOutputs();
		});

		function updateSubmitBtn() {
			if (selectedUPCs.length < billForm.find(".bill-item-fieldset").length) {
				billForm.find("button[type='submit']").attr("disabled", "disabled");
			} else {
				billForm.find("button[type='submit']").removeAttr("disabled");
			}
		}

		//updateOutputs();
        function getDiscountPercent(){
            $.ajax({
				url: "actions.php?action=get_discount_percent",
				type: "post",
				data: { card_number: billForm.find("#card_number").val() },
				success: function (response) {
					// Parse the JSON response
					var res = JSON.parse(response);
					if (res.error) {
						percent = 0;
					} else {
                        percent = parseInt(res);
                    }
                    
					
                    updateTotals();
				},
			});
        }
		function getProductInfo(fieldset) {
			$.ajax({
				url: "actions.php?action=get_product_info",
				type: "post",
				data: { UPC: fieldset.find(".UPC").val() },
				success: function (response) {
					// Parse the JSON response
					var data = JSON.parse(response);

					// Check if the response contains an error
					if (data.error) {
						console.log(data.error);
						return;
					}

					// Set the max attribute of the input with the name products_number
					fieldset.find(".product_number").attr("max", data.products_number);

					// Set the value of the output with the id selling_price
					//console.log(fieldset.find(".selling_price"));
					fieldset.find(".selling_price-output").text(parseFloat(data.selling_price).toFixed(2));
                    fieldset.find("#selling_price").val(parseFloat(data.selling_price).toFixed(2));
                    updateTotals();
				},
			});

            
		}
	}

    
    $(window).on("scroll", function(){
        
        var newScrollPosition = $(window).scrollTop();
        $.ajax({
            url: 'actions.php?action=update_scroll_position',
            type: 'post',
            data: {'scrollPosition':  newScrollPosition, "currentPage": currentPage},
            success: function(response) {
                // Handle response here
                
                //console.log(response);
                //scrollPosition = parseFloat(response);
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the error here
                console.log(textStatus, errorThrown);
            }
        });
    });
    $(window).scrollTop(scrollPosition);
    $('.add-form-container').on('toggle', function() {
        var detailsOpen = $(this).attr('open') ? 'open' : '';
        console.log(detailsOpen)
        $.ajax({
            url: 'actions.php?action=update_dialog_open',
            type: 'post',
            data: { 'detailsOpen': detailsOpen, "currentPage": currentPage},
            success: function(response) {
                // Handle response here
                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the error here
                console.log(textStatus, errorThrown);
            }
        });
    });
    
    $(".decimal").each(function(){
        $(this).text(parseFloat($(this).text()).toFixed(2))
    
    })
});
