function change_agent(path, agent){
    var selected_index = document.getElementById(agent).selectedIndex;
    var agent_id = document.getElementById(agent).options[selected_index].value;
    window.location.href = path+agent_id;
}
function selected_option_value(id){
    var selected_index = document.getElementById(id).selectedIndex;
    var value = document.getElementById(id).options[selected_index].value;
    return value;
}