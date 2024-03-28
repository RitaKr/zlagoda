$(document).ready(function () {
	//handles delete of item from database on delete button click. it sends request to actions.php where the sql query is executed, gets a response back and alerts the status of the action
	//note that in order for sql query to be executed each button must have data attributes with name of the table (data-table), name of PK (data-key), and id of item to be deleted (data-id)
	$(".delete").click(function (e) {
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
                const infoCells = td.siblings('td[data-info]');
				//in case of FK we use select
				if (fk) {
                    //console.log(fk);
					const fkArray = JSON.parse(fk);
                    let infoValues = fkArray.reduce((acc, pr)=>{
                        const {id, item_name, ...info} = pr;
                        acc[id] = Object.values(info);
                        return acc;
                    }, {});
                    console.log("infoValues:",infoValues);
					td.html(
						`<select name="${keyName}">
                        ${fkArray.map((fk) => {
                            const {id, item_name, ...info} = fk;
                            //console.log(info);
                            
							return `<option value="${id}" ${id == fkVal ? "selected" : ""}>${item_name}</option>`;}).join("")}</select>`
					);
                    const select = td.children("select");
                    select.on("change", function () {
                        const selected = $(this).find("option:selected");
                        const id = selected.val();
                                                
                        infoValues[id].forEach((info, i)=>{
                            infoCells[i].innerText = info;
                        })
                        
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
                        ${Object.entries(optionsObject).map(([key, val]) => {
                            return `<option value="${key}" ${text == val ? "selected" : ""}>${val}</option>`;
                        }).join("")}
                        </select>`);
                    
				} else {
					//otherwise text input
					td.html(
						`<input type="${type=='date'? 'date' : 'text'}" name="${keyName}" value="${text}" ${nn ? "required " : ""}
							 maxlength="${(maxlength ? maxlength : 50)}"
                             ${max ? 'max='+max: ''}>`
					);

					const input = td.children("input");
					input.on("keyup change input", function () {
                        
						if (this.required && input.val().trim() == "") {
							$(this).toggleClass("invalid", true);
						} else {
							$(this).toggleClass("invalid", false);
						}
                        if (type=="double") {
                            console.log(input.val(), parseFloat(input.val()), input.val()==parseFloat(input.val()));
                            if (isNaN(parseFloat(input.val())) || parseFloat(input.val()) < (min ? min : 0) || parseFloat(input.val())>max || input.val()!=parseFloat(input.val()) ) {
                                $(this).toggleClass("invalid", true);
                            } else {
                                $(this).toggleClass("invalid", false);
                            }
                        }
                        if (type=="int") {
                            console.log(input.val(), parseInt(input.val()), input.val()==parseInt(input.val()));
                            if (isNaN(parseInt(input.val())) || parseInt(input.val()) < (min ? min : 0) || parseInt(input.val())>max || input.val()!=parseInt(input.val())) {
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
});
