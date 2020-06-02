var app = {

  apiBaseUrl : 'http://api.okanban.local/',

  init: function() {
    console.log('init');

    

    // ajout de la getion de l'vent click sur le bouton d'ajout de liste
    //document.getElementById('addListButton').addEventListener('click', app.displayAddListModal);
    $('#addListButton').on('click', app.displayAddListModal);

    // Récupration de l'élément Modal pour ajouter une liste
    var addListModalElement = $('#addListModal');

      // Récupration de l'élément Modal pour ajouter une liste
      var addCardModalElement = $('#addCardModal');

    // Cibler tous les boutons de fermeture du modal on ajoute un EventListener sur tous les boutons qui déclenche la suppression de la classe 'is-active' de la modale (Qui permet de fermer la modal)
    $('.close').on('click', function(){
      addListModalElement.removeClass('is-active');
    });

    // Cibler tous les boutons de fermeture du modal on ajoute un EventListener sur tous les boutons qui déclenche la suppression de la classe 'is-active' de la modale (Qui permet de fermer la modal)
    $('.close').on('click', function(){
      addCardModalElement.removeClass('is-active');
    });

    // Cibler le formulaire d'ajout d'une nouvelle liste et ajouter un mouchard à l'événènement submit
    $('#formAddList').on('submit', app.handleAddListFormSubmit)

    // Cibler le formulaire d'ajout d'une nouvelle liste et ajouter un mouchard à l'événènement submit
    $('#formAddCard').on('submit', app.handleAddCardFormSubmit)

    // Cibler le formulaire de modification d'une liste et ajouter un mouchard à l'événènement submit
    $('#lists').on('submit', '#formUpdateList', app.handleUpdateListFormSubmit)

    // Retirer l'ensemble des listes
    $('#lists .panel').remove();

    // On ecoute la poubelle des cartes
    $('#lists').on('click', '.deleteCardButton', app.deleteCard);

    // On ecoute le style des cartes (pour modifier)
    $('#lists').on('click', '#updateCardButton', app.updateCard);

    // Lancer le chargement des listes
    app.loadLists();







  },


  loadLists: function(){ // Cette méthode déclenche l'appel Ajax
    $.ajax({
      url: app.apiBaseUrl + 'lists',
      dataType: 'json',
      method: 'GET'
    }).done(function(lists) { // et quand Ajax revient avec les données, on le dirige vers la méthode app.generateListSet
      app.generateListSet(lists);

      // d'après la doc
      // https://jqueryui.com/sortable/#connect-lists
      // pour utiliser le méthode sortable de jquery ui, il faut cibler les listes et executer la méthode sortable dessus
      console.log($(".panel-block"));
      $(".panel-block").sortable({
        connectWith: ".panel-block",
        //J'associe une methode à l'venemtn update de jquery ui
        update: app.handleSort,
      }).disableSelection();

    });

      // Lancer le chargement des listes
      app.loadCards();
  },

  handleSort: function(event, ui) {
    // dans l'event j'ai accès à event.target qui représente la liste cible
    // en mettant à jour le champ ordre de toutes les cartes qui sont dans la liste cible, et la liste de ces cards (list_id)
    // on est sur d'avoir l'ordre correct
    // console.log('sort :', event.target);
    
    // je veux mettre à jour toutes les cartes de la liste cible
    // 1. je récupère la liste cible
    let $list = $(event.target);
    // 1.1 je veux récupérer l'id de cette liste
    // je cible le parent direct grâce à la méthode jquery qui fait exactement ça
    let $listParent = $list.parent();
    let listId = $listParent.attr('data-list-id');

    // 2. je cible toutes les cartes dedans
    let $cards = $list.find('.box');
    // 3. pour chaque carte
    // https://api.jquery.com/each/
    // la fonction de rappel fournie à la méthode each prend en premier paramètre l'index de l'itération (0 puis 1 puis 2 etc) et le deuxième paramètre représente l'élement de l'itération en cours (ici chaque carte une à une)
    $cards.each(function(index, card) {
      // 3.1 je regarde l'id de la carte
      let cardId = $(card).attr('data-card-id');
      // 3.2 je fais une requete ajax pour solliciter l'api et faire persister l'ordre de la carte
      $.ajax({
        url: app.apiBaseUrl + 'cards/' + cardId + '/update',
        dataType: 'json',
        method: 'POST',
        data: {
          // on transmet l'ordre, index représente l'ordre dans le dom, on est bon
          listOrder: index,
          listId: listId,
        }
      });
    });
  },
  generateListSet: function(listsArray) {

    // ici, on va générer toutes les listes passées dans le tableau listsArray

    // équivalent du foreach en php
    $.each(listsArray, function(index, listObject){
      // ici, on passe 2 arguments car on doit recréer les lists avec leur id
      let clone = app.generateListElement(listObject.name);
      // On ajoute l'atribut id avec l'id récupéré dans le json en valeur pour chaques element liste cloné
      clone.attr('id', 'list-' + listObject.id);

      // ici l'attribut id permet de créer un identifiant unique dans tout le dom
      // moi je veux un moyen de stocker la donnée id de la liste, pour ça je vais utiliser un attribut data prévu pour ça 
      clone.attr('data-list-id', listObject.id);

      clone.insertBefore($('#buttonList'));
    });

  },
  handleAddListFormSubmit: function(evt) {
    //désactiver le fonctionnement par défaut
    evt.preventDefault();

    //récupérer la valeur dans l'input du formulaire
    let inputValue = $(evt.target).find('.input[name=list-name]').val();

    //appeler la méthode app.generateListElement (à créer à l'étape suivante) en donnant la valeur récupérée (de l'input) en paramètre
    let newList = app.generateListElement(inputValue);

    // pour l'étape 3, on va cibler la colonne du bouton d'ajout et utiliser insertBefore pour placer notre clone d'une liste en dernier parmie les listes, mais juste avant le bouton
    let buttonColumn = $('#buttonList');

    newList.insertBefore(buttonColumn);

    //Fermer la fenêtre Modal
    $('#addListModal').removeClass('is-active');

    app.addList(inputValue);

  },
  handleUpdateListFormSubmit: function (evt) {
      //désactiver le fonctionnement par défaut
      evt.preventDefault();
      console.log('click OK');

      //récupérer la valeur dans l'input du formulaire
      let inputValue = $(evt.target).find('.input[name=list-name]').val();
      let idList = $(evt.target).parents('.panel').attr('id')
      let id  = idList.substr(-1);

      app.updateList(inputValue, id);


  },
  updateList:function (inputValue, id) {

    $.ajax({
      url: app.apiBaseUrl + `lists/update/${id}`,
      dataType: 'json',
      method: 'POST',
      data : {
        listName : inputValue,
      }
    }).done((value) => {
      console.log(value)
    })
    .fail(() => {
      console.log("Erreur de modification de la liste : " + inputValue)
    });
  },
  generateListElement: function(listName) {
    
    // on clone le template pour récupérer une liste "vierge"
    let clone = $("#tplList").contents().clone();
    clone.find('h2').text(listName);
    clone.find('h2').on('dblclick', app.handleDisplayChangeListForm);
    clone.find('span').on('click', app.displayAddCardModal);

    return clone;

  },
  displayAddListModal: function(evt) {
    // Récupration de l'élément Modal
    let modal = $('#addListModal');
    // Ajout de la classe active
    modal.addClass('is-active');
  },
  handleDisplayChangeListForm: function(evt) {
    // Récupération de l'élément sur lequel l'event a eu lieu (jQuerysation)
    let h2Element = $(evt.target);

    // Pour cacher, il y a la classe "is-hidden" de Bulma
    h2Element.addClass('is-hidden');

    // On navigue jusqu'au formulaire
    let formElement = h2Element.next('form');

    // On affiche le formulaire en retirant la classe "is-hidden"
    formElement.removeClass('is-hidden');

    // On ajoute un mouchard sur le formulaire affiché
    formElement.on('submit', app.handleSubmitForm);

  },
  loadCards: function() {
    $.ajax({
      url: app.apiBaseUrl + 'cards',
      dataType: 'json',
      method: 'GET'
    }).done(function(cards) { // et quand Ajax revient avec les données, on le dirige vers la méthode app.generateListSet
      app.generateCardsSet(cards);
    });
  },
  generateCardsSet : function(cardsArray) {

    $.each(cardsArray, function(index, cardObject) {
      let clone = $("#tplCard").contents().clone();
      clone.find('#titleCard').text(cardObject.title);
      clone.attr('id', cardObject.id);
      clone.attr('data-card-id', cardObject.id);
      // Je cible la liste qui posséde l'id correspondant à l'attribut idList de ma card puis je cible la div qui doit recevoir les cards puis je l'injecte
      // Exemple pour 'Recoder oFramework' : clone.appendTo($('#2 .panel-block'));
      clone.appendTo($('#list-'+cardObject.list_id + ' .panel-block'));
    })



  },
  addList: function(inputValue) {
    $.ajax({
      url: app.apiBaseUrl + 'lists/add',
      dataType: 'json',
      method: 'POST',
      data : {
        listName : inputValue
      }
    }).done((value) => {
      console.log(value)
    })
    .fail(() => {
      console.log("Erreur d'ajout de la liste : " + inputValue)
    });
  },
  handleAddCardFormSubmit: function(evt) {
    //désactiver le fonctionnement par défaut
    evt.preventDefault();

    //récupérer la valeur dans l'input du formulaire
    let inputValue = $(evt.target).find('.input[name=card-name]').val();

    //appeler la méthode app.generateListElement (à créer à l'étape suivante) en donnant la valeur récupérée (de l'input) en paramètre
    let newCard = app.generateCardElement(inputValue);

    // pour l'étape 3, on va cibler la colonne du bouton d'ajout et utiliser insertBefore pour placer notre clone d'une liste en dernier parmie les listes, mais juste avant le bouton
    let buttonColumn = $('#buttonCard');

    newCard.insertBefore(buttonColumn);

    //Fermer la fenêtre Modal
    $('#addCardModal').removeClass('is-active');

  },
  generateCardElement: function(cardName) {
    
    // on clone le template pour récupérer une liste "vierge"
    let clone = $("#tplCard").contents().clone();
    clone.find('#titleCard').text(cardName);
    //clone.find('.has-text-danger').on('dblclick', app.deleteCard);

    return clone;

  },
  displayAddCardModal: function(evt) {
    let modal = $('#addCardModal');
    // Ajout de la classe active
    modal.addClass('is-active');
  },
  handleSubmitForm: function(evt) {
    //Stoper l'envoi du formulaire (Pas de rafraichissement de la page)
    evt.preventDefault();

    //Ciblage du form sur lequel on a cliqué, puis on recherche à l'intérieur de celui-ci les élèments qui on classe 'input' et l'attribut 'name' éagal à 'list-name' / .classe[attribut=valeur]
    let inputValue =  $(evt.target).find('.input[name=list-name]').val();

  },
  deleteCard: function(evt) {
    let idCard = $(evt.target).parents('.box').attr('id')
    let $card= $(evt.currentTarget).closest('.box');
    $.ajax({
      url: app.apiBaseUrl + `cards/delete/${idCard}`,
      dataType: 'json',
      method: 'GET',
    }).done(() => {
      $card.remove();
    }).fail(() => {
      alert('Suppression impossible')
    })
  },
  updateCard: function(evt) {
    console.log('modif bouton ok');
  }
};

// document.addEventListener('DOMContentLoaded', app.init);
$(app.init);