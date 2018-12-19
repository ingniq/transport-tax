(function ($) {
  'use strict';

  function set_zones() {
    $.getJSON(taxajax.url,
      {
        action: 'get_zones'
      },
      function (resp) {
        let html = "<option value='0' disabled selected>Выберете регион</option>";
        $(resp).each((index, element) => {
          html = html + "<option value='" + element.alias + "'>" + element.value + "</option>";
        });

        $("#reg").html(html);
      });
  }

  $(document).ready(function ($) {
    set_zones();
  });

  $('#reg').on('change', () => {
    $("#nalog_year").html('');
    $("#auto_type").html('');
    $("#warning").html('');
    $("#auto_brand").html('').parent().addClass('hidden');
    $("#auto_model").html('').parent().addClass('hidden');
    $("#model_year").val('');
    $("#benefit").html("<option value='0' selected>Нет</option>").parent().addClass('hidden');

    let zone = $("#reg option:selected").val();

    $.ajax({
      method: 'POST',
      url: taxajax.url,
      dataType: "json",
      data: {
        action: 'get_years',
        alias: zone
      }
    }).done(function (data) {
      let html = "<option value='0' disabled selected>Выберете год</option>";
      $(data).each((index, element) => {
        html = html + "<option value='" + element.ext_id + "'>" + element.value + "</option>";
      });

      $("#nalog_year").html(html);
    });

  });

  $('#nalog_year').on('change', () => {
    $("#auto_type").html('');
    $("#warning").html('');
    $("#auto_brand").html('').parent().addClass('hidden');
    $("#auto_model").html('').parent().addClass('hidden');
    $("#model_year").val('');

    let alias = $("#reg option:selected").val();
    let year = $("#nalog_year option:selected").val();

    $.ajax({
      method: 'POST',
      url: taxajax.url,
      dataType: "json",
      data: {
        action: 'get_categories',
        alias: alias,
        year: year
      }
    }).done(function (data) {
      let html = "<option value='0' disabled selected>Выберете категорию</option>";
      $(data).each((index, element) => {
        html = html + "<option value='" + element.ext_id + "'>" + element.value + "</option>";
      });

      $("#auto_type").html(html);
    });

    $.ajax({
      method: 'POST',
      url: taxajax.url,
      dataType: "json",
      data: {
        action: 'get_benefits',
        alias: alias,
        year: year
      }
    }).done(function (data) {
      let html = "<option value='0' selected>Нет</option>";
      $(data).each((index, element) => {
        html = html + "<option value='" + element.ext_id + "'>" + element.value + "</option>";
      });

      $("#benefit").html(html);
      $("#benefit").parent().removeClass('hidden');
    });

  });

  $('#auto_type').on('change', () => {
    $("#auto_brand").html('');
    $("#auto_model").html('');
    $("#model_year").val('');
    $("#warning").html('');

    let auto_type = $("#auto_type option:selected").val();

    $.ajax({
      method: 'POST',
      url: taxajax.url,
      dataType: "json",
      data: {
        action: 'get_brands',
        category: auto_type
      }
    }).done(function (data) {
      if ($(data).length !== 0) {

        let html = "<option value='0' disabled selected>Выберете марку</option>";
        $(data).each((index, element) => {
          html = html + "<option value='" + element.ext_id + "'>" + element.value + "</option>";
        });

        $("#auto_brand").html(html);
        $("#auto_brand").parent().removeClass('hidden');
      }
      else {
        $("#auto_brand").html('').parent().addClass('hidden');
        $("#auto_model").html('').parent().addClass('hidden');
        $("#model_year").val('');
      }
    });

  });

  $('#benefit').on('change', () => {
    $("#warning").html('');

  });

  $('#auto_brand').on('change', () => {
    $("#auto_model").html('');
    $("#warning").html('');

    let auto_brand = $("#auto_brand option:selected").val();

    $.ajax({
      method: 'POST',
      url: taxajax.url,
      dataType: "json",
      data: {
        action: 'get_models',
        brand: auto_brand
      }
    }).done(function (data) {
      if ($(data).length !== 0) {

        let html = "<option value='0' disabled selected>Выберете модель</option>";
        $(data).each((index, element) => {
          html = html + "<option value='" + element.value + "'>" + element.text + "</option>";
        });

        $("#auto_model").html(html);
        $("#auto_model").parent().removeClass('hidden');
      }
    });
  });

  $('#calculate').on('click', () => {
    let zone = $("#reg option:selected").val();
    let year = parseInt($("#nalog_year option:selected").val(), 10);
    let month = parseInt($("#month option:selected").val(), 10);
    let category = parseInt($("#auto_type option:selected").val(), 10);
    let benefit = parseInt($("#benefit option:selected").val(), 10);
    let power = parseInt($("#power").val(), 10);
    let brand = parseInt($("#auto_brand option:selected").val(), 10);
    let model = parseInt($("#auto_model option:selected").val(), 10);
    let model_year = parseInt($("#model_year").val(), 10);

    let data = {action: 'calculate_tax'};

    if (zone !== "0") {
      data.zone = zone
    }
    if (year) {
      data.year = year
    }
    if (month) {
      data.month = month
    }
    if (category) {
      data.category = category
    }

    if (benefit) {
      data.benefit = benefit
    }
    if (power) {
      data.power = power
    }
    if (brand) {
      data.brand = brand
    }
    if (model) {
      data.model = model
    }
    if (model_year) {
      data.model_year = model_year
    }

    if (data.zone && data.year && data.category) {

      if (data.brand) {
        if (!data.model) {
          $("#warning").html("<p>Не указана модель!</p>");
          return false;
        }
        if (data.model && !data.model_year) {
          $("#warning").html("<p>Не указан год выпуска!</p>");
          return false;
        }
        if (parseInt($("#nalog_year option:selected").text(), 10) < data.model_year) {
          $("#warning").html("<p>Значение поля 'Год' не может быть меньше значения поля 'Год выпуска транспортного средства'!</p>");
          return false;
        }
      }

      $.ajax({
        method: 'POST',
        url: taxajax.url,
        dataType: "json",
        data: data
      }).done(function (resp) {
        if ($(resp).length !== 0) {

          let html = "<p><strong><span>Расчет налога за " + resp.month + " мес. " + resp.year + " года:</span></strong>";
          html = html + "<span>Для транспортного средства: " + resp.category + "</span>";
          html = html + "<span>С мощностью двигателя: " + resp.power + "</span>";
          if (resp.rate) {
            html = html + "<strong><span>Ставка: " + resp.rate + "</span></strong>";
            if (resp.benefit) {
              html = html + "<span>Льгота: " + resp.benefit + "</span>";
            }
            if (resp.sub_benefit) {
              html = html + "<span class='warning'>" + resp.sub_benefit + "</span>";
            }
            html = html + "<strong><span>Сумма налога составит: " + resp.cost + "</span></strong>";
            html = html + "<span>Расчет произведен по формуле: " + resp.formula + "</span></p>";
          } else {
            html = html + "<span class='warning'>" + resp.info + "</span>";
          }

          $("#result").html(html);
        }
      });
    } else {
      $("#warning").html("<p>Не заполнены обязательные поля!</p>");
    }

  });


})(jQuery);
