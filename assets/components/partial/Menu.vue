<template>
<div>
    <div class="sidebar"> 
        <ul class="menu"> 
            <li :class="['menu-item',{active: item.active}]" v-for="item in menuData" :key="item.text"> 
                <a :href="item.url" class="menu-link"> 
                    <p :class="item.active ? 'active-label' : 'label'">{{ item.text }}</p> 
                </a> 
            </li> 
        </ul>  
    </div>
    <div class="header">
        <ul class="header-menu">
            <li :class="['header-menu-item',{active: item.active}]" v-for="item in subMenuData" :key="item.text">
                <a class="header-menu-link" :href="item.url">
                    <p :class="item.active ? 'active-label' : 'label'">{{item.text}}</p>
                </a>
            </li>
        </ul>
    </div>
</div>
</template> 
<script> 
export default { 
    name: "SidebarMenu", 
    props:{
        menuData:{
            type: Array,
            required: true,
        },
        subMenuData:{
            type: Array,
            required: true,
        },
    },
}; 
</script> 
<style scoped> 
.header { 
    position: fixed;
    z-index: 1000;
    top: 0; 
    width: 100vw; 
    height: 75px; 
    background-color: #3a4750; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); 
}
.sidebar { 
    position: fixed;
    z-index: 500;
    left: 0; 
    top: 0; 
    height: 
    100vh; 
    width: 75px; 
    background-color: #2c3e50; 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); 
} 
.menu { 
    list-style: none; 
    padding: 0; 
    margin: 75px 0 0 0 ; 
    width: 100%; 
} 
.menu-item { 
    width: 100%; 
} 
.menu-link { 
    display: flex; 
    flex-direction: column; 
    align-items: center; 
    justify-content: center; 
    text-decoration: none; 
    padding: 15px 0; 
} 

.icon { 
    font-size: 20px; 
} 
.label { 
    font-size: 15px; 
    margin-top: 5px; 
    color:#ecf0f1;  
}
.header{
    display: flex;
    align-content: center;
    justify-content: center;
} 
.header-menu{
    list-style: none;
    display: flex;
    align-items: center;
    justify-content: space-around;
    /* background: yellow; */
    min-width: 20%;

}
.header-menu-item{
    /* background: red; */
    padding: 20px;  
}
.header-menu-link{
    text-decoration: none;
}

.active{
    background: #ecf0f1;
}
.active-label{
    color: #2c3e50;
} 
</style>