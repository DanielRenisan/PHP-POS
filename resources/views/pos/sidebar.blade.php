<div class="sidebar" x-show="opneSidebar" @mouseleave="opneSidebar = false">
    <div class="container"
        :class="{ 'moreThanFiveCon': sidebarButtons.length > 5, 'lessThanFiveCon': sidebarButtons.length <= 5 }">
        <div class="buttons grid"
            :class="{ 'moreThanFiveBtns': sidebarButtons.length > 5, 'lessThanFiveBtns': sidebarButtons.length <= 5, }">
            <template x-for="(button, btnIndex) in filteredButtons" :key="btnIndex">
                <div @click="toggleSidebarMenu(btnIndex)">
                    <button :class="{ 'active': activeButtonIndex === btnIndex }">
                        <i style="font-size: 25px;" :class="button.icon"></i>
                        <span x-text="button.name"></span>
                    </button>
                    <div x-show.transition="button.openSubMenu" class="submenu"
                        @mouseleave="button.openSubMenu = false"
                        :class="{ 'moreThanFiveConSubMenu': sidebarButtons.length > 5, 'lessThanFiveConSubMenu': sidebarButtons.length <= 5 }">
                        <div class="grid grid-cols-2">
                            <template x-for="(item, index) in button.subMenu" :key="index">
                                <div>
                                    <a :href="item.link">
                                        <button>
                                            <!-- <i style="font-size: 25px;" :class="button.icon"></i> -->
                                            <span x-text="item.name"></span>
                                        </button>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>