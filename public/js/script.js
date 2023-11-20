//inputのアイコンをfade
$(".input").focusin(function () {
  $(this).find(".fade").animate({ opacity: "0" }, 200);
});

$(".input").focusout(function () {
  $(this).find(".fade").animate({ opacity: "1" }, 300);
});


//パスワードの可視性を切り替え
$(document).ready(function () {
  $(".eye").click(function () {
    let $previousInput = $(this).prev().prev(); // .fadeの前の要素、つまりinput要素を取得
    if ($previousInput.attr("type") === "password") {
      $previousInput.attr("type", "text");
      $(this).find("img").attr("src", "img/open.jpg"); // パスワードが見えるアイコンに変更
    } else {
      $previousInput.attr("type", "password");
      $(this).find("img").attr("src", "img/close.jpg"); // パスワードが見えないアイコンに変更
    }
  });
});


//行数が動的に変わる
$(function () {
  $(document).on("input", "textarea.auto_resize", function () {
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
    underlineTextarea($(this));
  });

  function underlineTextarea(textarea) {
    const container = textarea.closest(".textarea-wrap");
    const underlineContainer = container.find(".underline-container");
    underlineContainer.empty();

    const lineHeight = parseFloat(textarea.css("line-height"));

    // 改行の数を計算
    const lines = (textarea.val().match(/\n/g) || []).length + 1;

    //下線を引く
    for (let i = 1; i <= lines; i++) {
      const underline = $('<div class="underline"></div>');
      underline.css({
        top: i * lineHeight + "px",
      });
      underlineContainer.append(underline);
    }
  }
});

//鍵のオンオフの切り替え
$("#toggle, #toggle1, #toggle2").on("click", function () {
  const container = $(this).closest('.private');
  container.find(".lock-open").toggle();
  container.find(".lock").toggle();
});

//タブの切り替え
$(function() {
  $(".tab").on("click", function () {
    $(".tab, .panel").removeClass("active");

    $(this).addClass("active");

    var index =$(".tab").index(this);
    $(".panel").eq(index).addClass("active");
  })
})

//ミートボールメニューをクリックでモーダルを開く
$(".memory-meatball-menu").on("click", function () {
  $(".modal-container").addClass("active");
  return false;
});

//モーダルの外側をクリックしたらモーダルを閉じる
$(document).on("click", function (e) {
  if (!$(e.target).closest(".modal-body").length) {
    $(".modal-container").removeClass("active");
  }
});


//削除アラート
$(".delete-menu").on("click", function () {
  if (!confirm("削除しますか？")) {
    return false;
  } else {
    window.location.href = "memories.html";
  }
});

//ログアウトアラート
$("#logout").on("click", function () {
  if (!confirm("ログアウトしますか？")) {
    return false;
  } else {
    window.location.href = "login.html";
  }
});

//検索画面での友達追加ボタン
$("#friend-addition").on("click", function () {
  $(this).text("フォロー中");
  $(this).addClass("action");
  return false;
});

$(document).ready(function () {
  $("#friend-addition").click(function () {
    const $this = $(this);
    const isFollowing = $this.hasClass("following");

    if (isFollowing) {
      $this.removeClass("following");
      $this.text("フォロー");
      // ここでAPIに「フォロー解除」を通知
    } else {
      $this.addClass("following");
      $this.text("フォロー中");
      // ここでAPIに「フォロー」を通知
    }
  });
});

//AJAXのセットアップ
$.ajaxSetup({
  headers: {
      "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
  },
});

//プロフィール削除
$(document).ready(function () {
  $("#deleteProfileImageBtn").click(function () {
      // ユーザーに確認メッセージを表示
      if (confirm("本当にプロフィール画像を削除しますか？")) {
          // ここでAjaxなどを使用してサーバーに削除のリクエストを送ることができます
          $.ajax({
              url: "/profile-pic/delete", // これは実際のエンドポイントに置き換える必要があります
              method: "POST", // or DELETE or whatever method you want to use
              data: {
                  _token: $('meta[name="csrf-token"]').attr("content"), // CSRFトークンをLaravelに送信するために必要
              },
              success: function (response) {
                  if (response.success) {
                      alert("画像が削除されました。");
                      location.reload(); // ページを再読み込みして変更を反映する
                  } else {
                      alert("エラーが発生しました。再試行してください。");
                  }
              },
              error: function () {
                  alert("エラーが発生しました。再試行してください。");
              },
          });
      }
  });
});


// 画像プレビュー
$('#profile_pic').on('change', function(e) {
  const file = e.target.files[0];
  if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
          $('.myPage-img img').attr('src', e.target.result);
      }
      reader.readAsDataURL(file);
  }
});

